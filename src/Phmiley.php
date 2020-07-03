<?php
namespace FortyeightDesign\Phmiley;

require_once __DIR__ . '/PhmileyTrie.php';

class Phmiley
{
  private $unicodeVersion = '13.0';
  private $regExDataDir = __DIR__ . "/../regexdata";
  private $emojiRegEx = null;

  private $baseUrl = "https://unicode.org/Public/emoji/";

  private $preset = null;

  private $emojiSequenceIdentifiers = array(
    'Emoji_Keycap_Sequence',
    'Emoji_Flag_Sequence',
    'Emoji_Modifier_Sequence',
    'Emoji_Tag_Sequence',
    'Emoji_ZWJ_Sequence',
  );

  private $emojiClasses = array();

  public $tagGenerator = null;
  public $imgBase = '';
  public $imgExt = 'png';
  public $imgHeight = '1em';
  public $codeUppercase = false;

  public function __construct() {
    $this->tagGenerator = array($this, 'defaultTagGenerator');

    $this->setPreset('twemoji_72');
    
    $this->checkRegExFile();
  }

  public function setVersion($newVersion) {
    $this->unicodeVersion = $newVersion;
    $this->emojiRegEx = null;
    $this->checkRegExFile();
  }

  public function setPreset($preset) {    
    switch ($preset) {
      case 'twemoji_72':
        $this->imgBase = 'https://gitcdn.xyz/repo/twitter/twemoji/master/assets/72x72/';
        $this->imgExt = 'png';
      break;
      case 'twemoji_svg':
        $this->imgBase = 'https://gitcdn.xyz/repo/twitter/twemoji/master/assets/svg/';
        $this->imgExt = 'svg';
        break;
      case 'openmoji_svg':
        $this->imgBase = 'https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/svg/';
        $this->imgExt = 'svg';
        $this->imgHeight = '1.3em';
        $this->codeUppercase = true;
        break;
      case 'openmoji_72':
        $this->imgBase = 'https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/72x72/';
        $this->imgExt = 'png';
        $this->imgHeight = '1.3em';
        $this->codeUppercase = true;
        break;
      case 'openmoji_618':
        $this->imgBase = 'https://cdn.jsdelivr.net/gh/hfg-gmuend/openmoji@12.3.0/color/618x618/';
        $this->imgExt = 'png';
        $this->imgHeight = '1.3em';
        $this->codeUppercase = true;
        break;
      default:
        throw new Exception("Invalid preset '{$preset}'");
        break;
    }

    $this->preset = $preset;
  }

  public function parse($inputString) {
    $this->checkRegExFile();

    // fall back to default generator if it's set to a falsy value
    if (empty($this->tagGenerator)) {    
      $this->tagGenerator = array('self', 'defaultTagGenerator');
    }

    if (!is_callable($this->tagGenerator)) {
      throw new Exception("tagGenerator is not a callable!");
    }

    return preg_replace_callback($this->emojiRegEx, function($emoji) {
      $sequence = preg_split('//u', $emoji[0], -1, PREG_SPLIT_NO_EMPTY);

      return call_user_func($this->tagGenerator, [
        'emoji' => $emoji[0],
        'code' => implode('-', array_map([$this, 'toUnicode'], $sequence))
      ]);
    }, $inputString);
  }
  
  public static function toUnicode($emoji) {
    $emoji = mb_convert_encoding($emoji, 'UTF-32', 'UTF-8');
    $unicode = preg_replace("/^[0]+/", '', bin2hex($emoji));
    return $unicode;
  }

  public function defaultTagGenerator($data) {
    $code = $this->codeUppercase ? strtoupper($data['code']) : $data['code'];
    
    // special treatment for some file names
    if (preg_match('/^\d\d-/', $code)) {
      if (mb_strpos($this->preset, 'twemoji_') === 0) {
        $code = str_replace('-fe0f', '', $code);
      } else if (mb_strpos($this->preset, 'openmoji') === 0) {
        $code = "00{$code}";
      }
    }
    return '<img src="' . $this->imgBase . $code . '.' . $this->imgExt . '" style="height:' . ($this->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;">';
  }

  private function getData($dataUrl) {
    $sequenceData = @file($dataUrl);
    $preparedData = null;

    if (!$sequenceData) {
      throw new Exception("Unable to get emoji data from {$dataUrl}");
    } else {
      $preparedData = $this->prepareData($sequenceData);
    }

    return $preparedData;
  }

  private function prepareData($rawFileData) {
    $preparedLines = array();

    foreach ($rawFileData as $line) {
      // remove comments (but maybe save them to $comment in case we need that some time)
      $hashPosition = mb_strpos($line,'#');
      $comment = null;
      if (false !== $hashPosition) {
        $lineContent = mb_substr($line, 0, $hashPosition);
        $comment = trim(mb_substr($line, $hashPosition+1));
        $line = $lineContent;
      }

      // trim line and skip empty lines
      $line = trim($line);
      if (empty($line)) {
        continue;
      }

      // get Unicode information
      $line = explode(';', $line);

      // filter emoji classes
      $identifier = preg_replace("/^RGI_/", '', trim($line[1]));
      if (false === in_array($identifier,
        array_merge(
          array(
            'Emoji',
            'Emoji_Presentation',
          ),
          $this->emojiSequenceIdentifiers
        )
      )) {
        continue;
      }

      $lineData = array(
        'range' => trim($line[0]),
        'identifier' => $identifier,
        'comment' => $comment,
      );
      
      if (!isset($this->emojiClasses[$lineData['identifier']])) {
        $this->emojiClasses[$lineData['identifier']] = [];
      }
      
      $this->emojiClasses[$lineData['identifier']][] = $lineData['range'];

      $preparedLines[] = $lineData;
    }

    return $preparedLines;
  }

  private function parseRange($range) {
    $range = mb_strtoupper(trim($range));

    $unicodeRange = explode('..', $range);
    $unicodeSequence = explode(' ', $range);

    if (count($unicodeSequence) > 1) {
      return "\x{" . implode('}\x{', $unicodeSequence) . "}";
    } else if (1 === count($unicodeRange)) {
      return "\x{{$unicodeRange[0]}}";
    } else {
      return "\x{{$unicodeRange[0]}}-\x{{$unicodeRange[1]}}";
    }
  }

  private function getRegExFilename() {
    return __DIR__ . "/../regexdata/regex-{$this->unicodeVersion}.txt";
  }

  private function checkRegExFile() {
    if (null !== $this->emojiRegEx)  {
      return;
    }

    if (is_file($this->getRegExFilename())) {
      $this->emojiRegEx = file_get_contents($this->getRegExFilename());
      return;
    }

    $baseVersionUrl = "{$this->baseUrl}{$this->unicodeVersion}/";

    $emojiUrl = "{$baseVersionUrl}emoji-data.txt";
    if ($this->unicodeVersion === '13.0') {
      $emojiUrl = 'https://www.unicode.org/Public/13.0.0/ucd/emoji/emoji-data.txt';
    }
    $this->getData($emojiUrl);

    $sequenceUrl = "{$baseVersionUrl}emoji-sequences.txt";
    $this->getData($sequenceUrl);

    $sequenceZwjUrl = "{$baseVersionUrl}emoji-zwj-sequences.txt";
    $this->getData($sequenceZwjUrl);

    $rxReplacers = array_map(function ($emojiClass) {
      $regexpTrie = new PhmileyTrie();
      foreach ($emojiClass as $codePoints) {
        $regexpTrie->addLiteral(
          array_map(function ($singlePoint) {
            return $this->parseRange($singlePoint);
          }, explode(' ', $codePoints))
        );
      }
      
      return $this->emojiRegEx = $regexpTrie->build();
    }, $this->emojiClasses);

    $newRegEx = 
      '/\p{Emoji_Flag_Sequence}|\p{Emoji_Tag_Sequence}|\p{Emoji_ZWJ_Sequence}|\p{Emoji_Keycap_Sequence}|\p{Emoji_Modifier_Sequence}|\p{Emoji_Presentation}|\p{Emoji}\x{FE0F}/u';

    foreach ($rxReplacers as $abbr => $rx) {
      $newRegEx = preg_replace("/\\\p\{{$abbr}\}/", "(?:{$rx})", $newRegEx);
    }
    
    $this->emojiRegEx = $newRegEx;

    file_put_contents($this->getRegExFilename(), $this->emojiRegEx);
  }
}

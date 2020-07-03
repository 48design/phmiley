<?php
namespace FortyeightDesign\Phmiley;

/**
 * PhmileyTrie
 * based on the RegexpTrie class sters/php-regexp-trie (https://github.com/sters/php-regexp-trie)
 * Copyright (c) 2016 sters
 * available under the MIT license (https://raw.githubusercontent.com/sters/php-regexp-trie/master/LICENSE)
 */
class PhmileyTrie {

  public $head = array();

  public static function parseRange($range) {
    $range = mb_strtoupper(trim($range));
  
    $unicodeRange = explode('..', $range);
    $unicodeSequence = explode(' ', $range);

    if (empty($range)) {
      return null;
    }
  
    if (count($unicodeSequence) > 1) {
      return "\x{" . implode('}\x{', $unicodeSequence) . "}";
    } else if (1 === count($unicodeRange)) {
      return "\x{{$unicodeRange[0]}}";
    } else {
      return "\x{{$unicodeRange[0]}}-\x{{$unicodeRange[1]}}";
    }
  }

  public static function minifyRanges(&$rangesArray) {
    self::sortCodePoints($rangesArray);

    $copy = $rangesArray;
  
    $rangesArray = array();
    $previous = '';
  
    foreach ($copy as $codeRange) {
      $previousRange = explode('..', $previous);
      $thisRange = explode('..', $codeRange);
      $previousIsRange = count($previousRange) > 1;
      $thisIsRange = count($thisRange) > 1;
  
      if (empty($rangesArray) || strpos($codeRange, ' ') !== false || strpos($previous, ' ') !== false) {
        $rangesArray[] = $codeRange;
      } else {
        $thisValue = $thisIsRange ? $thisRange[0] : $codeRange;
        $previousValue = $previousIsRange ? $previousRange[1] : $previous;
        
        // this first condition shouldn't be needed, as duplicate entries are already handled in addLiteral()
        /*if ((!$previousIsRange && !$thisIsRange && hexdec($previous) === hexdec($codeRange)) || $previous === $codeRange) {
          // same, skip the second one
          $codeRange = null;
        } else*/ if (hexdec($previousValue) === (hexdec($thisValue) - 1)) {
          // difference is 1, merge together as single range
          array_pop($rangesArray);
          $codeRange = ($previousIsRange ? $previousRange[0] : $previous) . '..' . ($thisIsRange ? $thisRange[1] : $codeRange);
        } else if (!$previousIsRange && $thisIsRange
          && hexdec($previous) >= hexdec($thisRange[0])
          && hexdec($previous) <= hexdec($thisRange[1])
        ) {
          // previous is a single value and inside the current range, replace previous with this range
          array_pop($rangesArray);
        } else if ($previousIsRange && !$thisIsRange
          && hexdec($codeRange) >= hexdec($previousRange[0])
          && hexdec($codeRange) <= hexdec($previousRange[1])
        ) {
          // this is a single range and inside the previous range, omit this value
          $codeRange = null;
        } else if (
          // merge two overlapping ranges into a single range
          $previousIsRange && $thisIsRange
          && (
             hexdec($thisRange[0]) >= hexdec($previousRange[0]) && hexdec($thisRange[0]) <= hexdec($previousRange[1])
          || hexdec($thisRange[1]) >= hexdec($previousRange[0]) && hexdec($thisRange[1]) <= hexdec($previousRange[1])
          )
        ) {
          array_pop($rangesArray);
          $codeRange = min($previousRange[0], $thisRange[0]) . '..' . max($previousRange[1], $thisRange[1]);
        } else if ($previousIsRange && hexdec($previousRange[0]) === (hexdec($previousRange[1]) - 1)) {
          // the previous range spans only two code points, which would make the regex actually larger, so separate them instead
          array_pop($rangesArray);
          $rangesArray[] = $previousRange[0];
          $rangesArray[] = $previousRange[1];
        }
    
        if ($codeRange) {
          $rangesArray[] = $codeRange;
        }
      }
      
      $previous = end($rangesArray);
    }
  }

  public static function sortCodePoints(&$codePoints) {
    usort($codePoints, function ($a, $b) {
      $isSequenceA = (strpos($a, ' ') !== false);
      $isSequenceB = (strpos($b, ' ') !== false);   
  
      if ($isSequenceA && $isSequenceB) {
        return strcmp($a, $b);
      } else if ($isSequenceA) {
        return 1;
      } else if ($isSequenceB) {
        return -1;
      } else {
        $rangeA = explode('..', $a);
        $rangeB = explode('..', $b);
        if (count($rangeA) > count($rangeB)) {
          // a is a range, b is a single value
          return (hexdec($rangeA[0]) - hexdec($b)) ?: 1;
        } else if (count($rangeB) > count($rangeA)) {
          // b is a range, a is a single value
          return (hexdec($a) - hexdec($rangeB[0])) ?: -1;
        } else if (count($rangeA) === 1 && count($rangeA) === count($rangeB)) {
          // both are single values
          return hexdec($a) - hexdec($b);
        } else {
          // both are ranges
          return hexdec($rangeA[0]) - hexdec($rangeB[0]);
        }
      }
    });
  }

  public function addLiteral($arr)
  {
    if (empty($arr) || !is_array($arr)) {
      throw new \InvalidArgumentException('$arr must be array.');
    }

    $head = &$this->head;

    foreach ($arr as $key) {
      if (!isset($head[$key])) {
        $head[$key] = [];
      }

      $head = &$head[$key];
    }

    $head['end'] = false;

    return $this;
  }

  public function build(array $entry = null)
  {
    if (is_null($entry)) {
      $entry = $this->head;
    }

    if (empty($entry) || (count($entry) === 1 && isset($entry['end']))) {
      return null;
    }

    $alt = [];
    $cc = [];
    $q = false;

    foreach ($entry as $key => $value) {
      $qc = $key;

      if (empty($value)) {
        $q = true;
        continue;
      }

      $recurse = $this->build($value);
      if (is_null($recurse)) {
        $cc[] = $qc;
      } else {
        $alt[] = self::parseRange($qc) . $recurse;
      }
    }

    $cconly = empty($alt);
    if (!empty($cc)) {
      $ccSingle = count($cc) === 1;

      self::minifyRanges($cc);
      
      if ($ccSingle) {
        $cc = self::parseRange($cc[0]);
        if (false !== strpos($cc, '-')) {
          $cc = "[{$cc}]";
        }
      }
      $alt[] = $ccSingle ? $cc : ('[' . implode('', array_map(['self', 'parseRange'], $cc)) . ']');
    }

    $result = count($alt) === 1 ? $alt[0] : ('(?:' . implode('|', $alt) . ')');

    if ($q) {
      if ($cconly) {
        $result = $result . '?';
      } else {
        $result = '(?:' . $result . ')?';
      }
    }

    return $result;
  }
}
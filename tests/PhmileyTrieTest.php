<?php
namespace FortyeightDesign\Phmiley\Test;

use FortyeightDesign\Phmiley\PhmileyTrie;

class PhmileyTrieTest extends \PHPUnit\Framework\TestCase
{
    public function testBuild()
    {
        $trie = new PhmileyTrie();
        $trie->addLiteral(array(
            '0009',
            'F000',
            '000A',
            '0012',
            'EFFF',
            'EFFF AAAB BBBB',
            'EFFF AAAA BBBB',
            'A000',
            '0010',
            'AAAA..EFFE',
            'AAAA',
            'EFFF 0AAB BBBB',
            '0008',
            '0009..0011',
            '0003',
            '0003..0009',
          ));
        $this->assertEquals('\x{0009}\x{F000}\x{000A}\x{0012}\x{EFFF}\x{EFFF}\x{AAAB}\x{BBBB}\x{EFFF}\x{AAAA}\x{BBBB}\x{A000}\x{0010}\x{AAAA}-\x{EFFE}\x{AAAA}\x{EFFF}\x{0AAB}\x{BBBB}\x{0008}\x{0009}-\x{0011}\x{0003}[\x{0003}-\x{0009}]', $trie->build());
    }

    public function testMinifyRanges()
    {
        $trie = new PhmileyTrie();

        $trieData = array(
            'EFFF AAAB BBBB',
            'EFFF AAAA BBBB',
            'EFFF AAAA BBBB CCCC',
            'A000',
            '0010',
            'AAAA..EFFE',
            'AAAA',
            'EFFF 0AAB BBBB',
            '0008',
            '0009..0011',
            '0008',
            '0012',
            'EFFF',
            '4849',
            '4848',
            'F000',
            '0048',
            '0048',
            '',
            null,
            '0003..0009',
            '0003..0009',
        );

        foreach ($trieData as $codePoints) {
            $trie->addLiteral(
                array_map(function ($singlePoint) {
                return ($singlePoint);
                }, explode(' ', $codePoints))
            );
        }

        $this->assertEquals('(?:\x{EFFF}(?:(?:\x{AAAB}\x{BBBB}|\x{AAAA}\x{BBBB}\x{CCCC}?|\x{0AAB}\x{BBBB}))?|[\x{0003}-\x{0012}\x{0048}\x{4848}\x{4849}\x{A000}\x{AAAA}-\x{EFFE}\x{F000}])', $trie->build());
    }

    public function testSortCodePoints() {
        $codePoints = array(
            'AAAA..CCCC',
            'AAAA',
            'DDDD',
            'DDDD..FFFF',
            '0010',
            '1000',
            '00AA ABCD',
            '0100',
            '0001',
            '00A0 ABCD'
        );

        $expectedCodePoints = array(
            '0001',
            '0010',
            '0100',
            '1000',
            'AAAA',
            'AAAA..CCCC',
            'DDDD',
            'DDDD..FFFF',
            '00A0 ABCD',
            '00AA ABCD'
        );

        PhmileyTrie::sortCodePoints($codePoints);

        $this->assertEquals($expectedCodePoints, $codePoints);
    }

    public function testAddLiteralStringArgumentThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);    
        
        $trie = new PhmileyTrie();
        $trie->addLiteral('foo');
    }
}
?>
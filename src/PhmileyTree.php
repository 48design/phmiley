<?php
namespace FortyeightDesign\Phmiley;

use RegexpTrie\RegexpTrie;

/**
 * PhmileyTree
 * based on the RegexpTrie class sters/php-regexp-trie (https://github.com/sters/php-regexp-trie)
 * available under the MIT license (https://raw.githubusercontent.com/sters/php-regexp-trie/master/LICENSE)
 */
class PhmileyTree {
    public static function union($strings = [])
    {
        return new static($strings);
    }

    public function __construct($strings = [])
    {
        $this->head = [];
        $this->add($strings);
    }

    private function __nextchar($string, &$pointer){
        if (!isset($string[$pointer])) {
            return false;
        }

        $char = ord($string[$pointer]);
        if ($char < 128) {
            return $string[$pointer++];
        }

        if ($char < 224){
            $bytes = 2;
        } elseif ($char < 240) {
            $bytes = 3;
        } elseif ($char < 248) {
            $bytes = 4;
        } elseif ($char == 252) {
            $bytes = 5;
        } else {
            $bytes = 6;
        }
        $str =  substr($string, $pointer, $bytes);
        $pointer += $bytes;
        return $str;
    }

    public function add($str)
    {
        if (is_array($str)) {
            foreach ($str as $s) {
                $this->add($s);
            }

            return $this;
        }

        if (empty($str) || !is_string($str)) {
            throw new \InvalidArgumentException('$str must be string.');
        }

        $head = &$this->head;

        $pointer = 0;
        while (($char = $this->__nextchar($str, $pointer)) !== false) {
            if (!isset($head[$char])) {
                $head[$char] = [];
            }

            $head = &$head[$char];
        }

        $head['end'] = false;

        return $this;
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
            $qc = stripslashes($key) === $key ? preg_quote($key, '/') : $key;

            if (empty($value)) {
                $q = true;
                continue;
            }

            $recurse = $this->build($value);
            if (is_null($recurse)) {
                $cc[] = $qc;
            } else {
                $alt[] = $qc . $recurse;
            }
        }

        $cconly = empty($alt);
        if (!empty($cc)) {
            $alt[] = count($cc) === 1 ? $cc[0] : ('[' . implode('', $cc) . ']');
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
<?php

namespace App\Lib;

class Hexer {
    public static function encode(int $id, $padding = 5, $randomHash = false, $hashAlgo = 'md5') {
        $hashInput = $randomHash ? rand() : $id;
        return substr(hash($hashAlgo, $hashInput), 0, $padding) . dechex($id) . substr(hash($hashAlgo, $hashInput), -$padding, $padding);
    }

    public static function decode($encodedString, $padding = 5) {
        return hexdec(substr(substr($encodedString, 0, -$padding), $padding));
    }
}

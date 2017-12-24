<?php declare(strict_types=1);

/**
 * @return string
 * @throws Error
 */
function get_float_code(): string
{
    static $code;

    if (isset($code)) {
        return $code;
    }

    $bin = \pack('e', 0.1);

    if (\strlen($bin) === 4) {
        return $code = 'g';
    }

    $bin = \pack('g', 0.1);

    if (\strlen($bin) === 4) {
        return $code = 'e';
    }

    throw new \Error('Cannot pack()/unpack() floating point numbers to a 32-bit little-endian representation');
}

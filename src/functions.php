<?php declare(strict_types=1);

namespace DaveRandom\LifxLan;

if (\strlen(\pack('e', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'e');
} else if (\strlen(\pack('g', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'g');
} else {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new \Error('Cannot pack()/unpack() floating point numbers to a 32-bit little-endian representation');
}

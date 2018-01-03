<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

// @codeCoverageIgnoreStart
if (\strlen(\pack('e', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'e');
} else if (\strlen(\pack('g', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'g');
} else {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new \Error('Cannot pack()/unpack() floating point numbers to a 32-bit little-endian representation');
}

const UINT32_MIN = \PHP_INT_SIZE === 4 ? \PHP_INT_MIN : 0;
const UINT32_MAX = \PHP_INT_SIZE === 4 ? \PHP_INT_MIN : 0xffffffff;
// @codeCoverageIgnoreEnd

/**
 * @param \DateTimeInterface $dateTime
 * @return \DateTimeImmutable
 */
function datetimeinterface_to_datetimeimmutable(\DateTimeInterface $dateTime): \DateTimeImmutable
{
    if ($dateTime instanceof \DateTimeImmutable) {
        return $dateTime;
    }

    \assert($dateTime instanceof \DateTime, new \Error('DateTimeInterface is not DateTimeImmutable or DateTime???'));

    return \DateTimeImmutable::createFromMutable($dateTime);
}

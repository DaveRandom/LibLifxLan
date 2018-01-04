<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

// @codeCoverageIgnoreStart
use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

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

/**
 * @param int $timestamp
 * @return \DateTimeImmutable
 * @throws InvalidValueException
 */
function nanotime_to_datetimeimmutable(int $timestamp): \DateTimeImmutable
{
    static $utcTimeZone;

    $usecs = (int)(($timestamp % 1000000000) / 1000);
    $secs = (int)($timestamp / 1000000000);

    $result = \DateTimeImmutable::createFromFormat(
        'u U',
        \sprintf("%06d %d", $usecs, $secs),
        $utcTimeZone ?? ($utcTimeZone = new \DateTimeZone('UTC'))
    );

    if ($result === false) {
        throw new InvalidValueException("Could not convert nanotime to DateTimeImmutable instance");
    }

    return $result;
}

/**
 * @param \DateTimeInterface $dateTime
 * @return int
 * @throws InvalidValueException
 */
function datetimeinterface_to_nanotime(\DateTimeInterface $dateTime): int
{
    $result = ($dateTime->format('U') * 1000000000) + ($dateTime->format('u') * 1000);

    if ($result < 0) {
        throw new InvalidValueException("Timestamp {$dateTime->format('Y-m-d H:i:s.u')} is negative");
    }

    return $result;
}

function int16_to_uint16(int $signed): int
{
    return $signed < 0
        ? ($signed & 0x7fff) + 0x8000
        : $signed & 0x7fff;
}

function uint16_to_int16(int $unsigned): int
{
    return $unsigned & 0x8000
        ? ($unsigned & 0x7fff) - 0x8000
        : $unsigned & 0x7fff;
}

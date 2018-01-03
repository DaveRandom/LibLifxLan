<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

if (\strlen(\pack('e', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'e');
} else if (\strlen(\pack('g', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'g');
} else {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new \Error('Cannot pack()/unpack() floating point numbers to a 32-bit little-endian representation');
}

\define(__NAMESPACE__ . '\\UINT32_MIN', \PHP_INT_SIZE === 4 ? \PHP_INT_MIN : 0);
\define(__NAMESPACE__ . '\\UINT32_MAX', \PHP_INT_SIZE === 4 ? \PHP_INT_MIN : 0xffffffff);

/**
 * @param \DateTimeInterface $dateTime
 * @return \DateTimeImmutable
 * @throws InvalidValueException
 */
function datetimeinterface_to_datetimeimmutable(\DateTimeInterface $dateTime): \DateTimeImmutable
{
    if ($dateTime instanceof \DateTimeImmutable) {
        return $dateTime;
    }

    if ($dateTime instanceof \DateTime) {
        return \DateTimeImmutable::createFromMutable($dateTime);
    }

    $result = \DateTimeImmutable::createFromFormat('u U', $dateTime->format('u U'));

    if ($result === false) {
        throw new InvalidValueException('Could not create DateTimeImmutable instance from DateTimeInterface instance');
    }

    return $result;
}

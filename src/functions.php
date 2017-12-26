<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

if (\strlen(\pack('e', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'e');
} else if (\strlen(\pack('g', 0.1)) === 4) {
    \define(__NAMESPACE__ . '\\FLOAT32_CODE', 'g');
} else {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new \Error('Cannot pack()/unpack() floating point numbers to a 32-bit little-endian representation');
}

function datetimeinterface_to_datetimeimmutable(\DateTimeInterface $dateTime): \DateTimeImmutable
{
    if ($dateTime instanceof \DateTimeImmutable) {
        return $dateTime;
    }

    if ($dateTime instanceof \DateTime) {
        return \DateTimeImmutable::createFromMutable($dateTime);
    }

    return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.u', $dateTime->format('Y-m-d H:i:s.u'));
}

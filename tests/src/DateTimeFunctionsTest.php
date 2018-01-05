<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use PHPUnit\Framework\TestCase;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;
use function DaveRandom\LibLifxLan\datetimeinterface_to_nanotime;
use function DaveRandom\LibLifxLan\nanotime_to_datetimeimmutable;

final class DateTimeFunctionsTest extends TestCase
{
    private const DATETIME_FORMAT_STRING = 'Y-m-d H:i:s.u';

    private const DATES = [
        '1677-09-21 00:12:44.854775' => ['time' => -9223372036, 'usecs' => '854775', 'nano' => -9223372036854775000],
        '1970-01-01 00:00:00.000000' => ['time' => 0, 'usecs' => '000000', 'nano' => 0],
        '2018-01-03 16:55:42.053987' => ['time' => 1514998542, 'usecs' => '053987', 'nano' => 1514998542053987000],
        '2262-04-11 23:47:16.854775' => ['time' => 9223372036, 'usecs' => '854775', 'nano' => 9223372036854775000],
    ];

    private const TIMEZONE_NAME = 'UTC';

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTime(): void
    {
        foreach (self::DATES as $string => ['time' => $time, 'usecs' => $usecs]) {
            $date = \DateTime::createFromFormat(self::DATETIME_FORMAT_STRING, $string, new \DateTimeZone(self::TIMEZONE_NAME));
            $result = datetimeinterface_to_datetimeimmutable($date);

            $this->assertInstanceOf(\DateTimeImmutable::class, $result);
            $this->assertSame($result->format('U u'), "{$time} {$usecs}");
            $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
        }
    }

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTimeImmutable(): void
    {
        foreach (self::DATES as $string => ['time' => $time, 'usecs' => $usecs]) {
            $date = \DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT_STRING, $string, new \DateTimeZone(self::TIMEZONE_NAME));
            $result = datetimeinterface_to_datetimeimmutable($date);

            $this->assertInstanceOf(\DateTimeImmutable::class, $result);
            $this->assertSame($result->format('U u'), "{$time} {$usecs}");
            $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
        }
    }

    public function testDateTimeInterfaceToNanotimeWithDateTime(): void
    {
        foreach (self::DATES as $string => ['nano' => $nanotime]) {
            $date = \DateTime::createFromFormat(self::DATETIME_FORMAT_STRING, $string, new \DateTimeZone(self::TIMEZONE_NAME));
            $this->assertSame(datetimeinterface_to_nanotime($date), $nanotime);
        }
    }

    public function testDateTimeInterfaceToNanotimeWithDateTimeImmutable(): void
    {
        foreach (self::DATES as $string => ['nano' => $nanotime]) {
            $date = \DateTime::createFromFormat(self::DATETIME_FORMAT_STRING, $string, new \DateTimeZone(self::TIMEZONE_NAME));
            $this->assertSame(datetimeinterface_to_nanotime($date), $nanotime);
        }
    }

    public function testNanotimeToDateTimeImmutable(): void
    {
        foreach (self::DATES as $string => ['nano' => $nanotime]) {
            $this->assertSame(nanotime_to_datetimeimmutable($nanotime)->format(self::DATETIME_FORMAT_STRING), $string);
        }
    }
}

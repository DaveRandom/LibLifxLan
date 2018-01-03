<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use PHPUnit\Framework\TestCase;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class FunctionsTest extends TestCase
{
    private const FORMAT_STRING = '2018-01-03 16:55:42.053987';
    private const DATE_STRING = '2018-01-03 16:55:42.053987';
    private const TIMEZONE_NAME = 'UTC';

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTime(): void
    {
        $date = \DateTime::createFromFormat(self::FORMAT_STRING, self::DATE_STRING, new \DateTimeZone(self::TIMEZONE_NAME));
        $result = datetimeinterface_to_datetimeimmutable($date);

        $this->assertInstanceOf(\DateTimeImmutable::class, $result);
        $this->assertSame($result->format(self::FORMAT_STRING), self::DATE_STRING);
        $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
    }

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTimeImmutable(): void
    {
        $date = \DateTimeImmutable::createFromFormat(self::FORMAT_STRING, self::DATE_STRING, new \DateTimeZone(self::TIMEZONE_NAME));
        $result = datetimeinterface_to_datetimeimmutable($date);

        $this->assertInstanceOf(\DateTimeImmutable::class, $result);
        $this->assertSame($result->format(self::FORMAT_STRING), self::DATE_STRING);
        $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
    }
}

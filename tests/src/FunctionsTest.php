<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use function DaveRandom\LibLifxLan\int16_to_uint16;
use function DaveRandom\LibLifxLan\uint16_to_int16;
use PHPUnit\Framework\TestCase;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class FunctionsTest extends TestCase
{
    private const INT16_CONVERSIONS = [
        0 => 0,
        32767 => 32767,
        32768 => -32768,
        32769 => -32767,
        65535 => -1,
    ];

    private const DATETIME_FORMAT_STRING = '2018-01-03 16:55:42.053987';
    private const DATE_STRING = '2018-01-03 16:55:42.053987';
    private const TIMEZONE_NAME = 'UTC';

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTime(): void
    {
        $date = \DateTime::createFromFormat(self::DATETIME_FORMAT_STRING, self::DATE_STRING, new \DateTimeZone(self::TIMEZONE_NAME));
        $result = datetimeinterface_to_datetimeimmutable($date);

        $this->assertInstanceOf(\DateTimeImmutable::class, $result);
        $this->assertSame($result->format(self::DATETIME_FORMAT_STRING), self::DATE_STRING);
        $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
    }

    public function testDateTimeInterfaceToDateTimeImmutableWithDateTimeImmutable(): void
    {
        $date = \DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT_STRING, self::DATE_STRING, new \DateTimeZone(self::TIMEZONE_NAME));
        $result = datetimeinterface_to_datetimeimmutable($date);

        $this->assertInstanceOf(\DateTimeImmutable::class, $result);
        $this->assertSame($result->format(self::DATETIME_FORMAT_STRING), self::DATE_STRING);
        $this->assertSame($result->getTimezone()->getName(), self::TIMEZONE_NAME);
    }

    public function testInt16ToUint16(): void
    {
        foreach (self::INT16_CONVERSIONS as $uint16 => $int16) {
            $this->assertSame(int16_to_uint16($int16), $uint16, "int16({$int16}) => uint16({$uint16})");
        }
    }

    public function testInt16ToUint16WithOverflowedInputs(): void
    {
        $tests = [
            -32771 => 32765,
            -32770 => 32766,
            -32769 => 32767,
            32768 => 0,
            32769 => 1,
            32770 => 2,
        ];

        foreach ($tests as $int16 => $uint16) {
            $this->assertSame(int16_to_uint16($int16), $uint16, "int16({$int16}) => uint16({$uint16})");
        }
    }

    public function testUint16ToInt16(): void
    {
        foreach (self::INT16_CONVERSIONS as $uint16 => $int16) {
            $this->assertSame(uint16_to_int16($uint16), $int16, "uint16({$uint16}) => int16({$int16})");
        }
    }

    public function testUInt16ToInt16WithOverflowedInputs(): void
    {
        $tests = [
            -3 => 32765,
            -2 => 32766,
            -1 => 32767,
            65536 => 0,
            65537 => 1,
            65538 => 2,
        ];

        foreach ($tests as $uint16 => $int16) {
            $this->assertSame(uint16_to_int16($uint16), $int16, "int16({$int16}) => uint16({$uint16})");
        }
    }
}

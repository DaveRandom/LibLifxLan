<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_int16;
use function DaveRandom\LibLifxLan\validate_uint16;
use function DaveRandom\LibLifxLan\validate_uint32;
use function DaveRandom\LibLifxLan\validate_uint8;
use PHPUnit\Framework\TestCase;
use function DaveRandom\LibLifxLan\validate_int_range;

final class IntValidationFunctionsTest extends TestCase
{
    public function testValidateIntRangeWithValidValues(): void
    {
        $tests = [
            0 => ['min' => 0, 'max' => 0],
            1 => ['min' => 0, 'max' => 2],
            42 => ['min' => 0, 'max' => 100],
            -6 => ['min' => \PHP_INT_MIN, 'max' => \PHP_INT_MAX],
        ];

        foreach ($tests as $int => ['min' => $min, 'max' => $max]) {
            $this->assertEquals(validate_int_range('Test', $int, $min, $max), $int);
        }
    }

    public function testValidateIntRangeWithInvalidValues(): void
    {
        $tests = [
            0 => ['min' => 1, 'max' => 2],
            1 => ['min' => -42, 'max' => -30],
            42 => ['min' => 1, 'max' => 2],
            -6 => ['min' => 5, 'max' => 7],
        ];

        $failures = 0;

        foreach ($tests as $int => ['min' => $min, 'max' => $max]) {
            try {
                validate_int_range('Test', $int, $min, $max);
            } catch (InvalidValueException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count($tests));
    }

    public function testValidateUint8WithValidValues(): void
    {
        $tests = [0, 42, 255];

        foreach ($tests as $int) {
            $this->assertEquals(validate_uint8('Test', $int), $int);
        }
    }

    public function testValidateUint8WithInvalidValues(): void
    {
        $tests = [-1, 256];

        $failures = 0;

        foreach ($tests as $int) {
            try {
                validate_uint8('Test', $int);
            } catch (InvalidValueException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count($tests));
    }

    public function testValidateInt16WithValidValues(): void
    {
        $tests = [-32768, 0, 42, 32767];

        foreach ($tests as $int) {
            $this->assertEquals(validate_int16('Test', $int), $int);
        }
    }

    public function testValidateInt16WithInvalidValues(): void
    {
        $tests = [-32769, 32768];

        $failures = 0;

        foreach ($tests as $int) {
            try {
                validate_int16('Test', $int);
            } catch (InvalidValueException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count($tests));
    }

    public function testValidateUint16WithValidValues(): void
    {
        $tests = [0, 42, 256, 65535];

        foreach ($tests as $int) {
            $this->assertEquals(validate_uint16('Test', $int), $int);
        }
    }

    public function testValidateUint16WithInvalidValues(): void
    {
        $tests = [-1, 65536];

        $failures = 0;

        foreach ($tests as $int) {
            try {
                validate_uint16('Test', $int);
            } catch (InvalidValueException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count($tests));
    }

    public function testValidateUint32WithValidValues(): void
    {
        $tests = [0, 42, 256, 65536, 4294967295];

        foreach ($tests as $int) {
            $this->assertEquals(validate_uint32('Test', $int), $int);
        }
    }

    public function testValidateUint32WithInvalidValues(): void
    {
        $tests = [-1, 4294967296];

        $failures = 0;

        foreach ($tests as $int) {
            try {
                validate_uint32('Test', $int);
            } catch (InvalidValueException $e) {
                $failures++;
            }
        }

        $this->assertSame($failures, \count($tests));
    }
}

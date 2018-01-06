<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use DaveRandom\LibLifxLan\Enum;
use PHPUnit\Framework\TestCase;

final class TestEnum extends Enum
{
    public const ONE = 1;
    public const TWO = 2;
    public const THREE = 1;
}

final class EnumTest extends TestCase
{
    public function testToArray()
    {
        $this->assertSame(TestEnum::toArray(), ['ONE' => 1, 'TWO' => 2, 'THREE' => 1]);
    }

    public function testParseNameWithExistingName()
    {
        $this->assertSame(TestEnum::parseName('ONE'), 1);
    }

    public function testParseNameWithExistingNameCaseInsensitive()
    {
        $this->assertSame(TestEnum::parseName('one', true), 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseNameWithExistingNameCaseInsensitiveDisabled()
    {
        TestEnum::parseName('one');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseNameWithNonExistentName()
    {
        TestEnum::parseName('FOUR');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseNameWithNonExistentNameCaseInsensitive()
    {
        TestEnum::parseName('four', true);
    }

    public function testParseValueWithExistingValue()
    {
        $this->assertSame(TestEnum::parseValue(1), 'ONE');
    }

    public function testParseValueWithExistingValueLoose()
    {
        $this->assertSame(TestEnum::parseValue('1', true), 'ONE');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseValueWithExistingValueLooseDisabled()
    {
        TestEnum::parseValue('1');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParseValueWithNonExistentValue()
    {
        TestEnum::parseValue(4);
    }

    /**
     * @expectedException \Error
     */
    public function testEnumCannotBeInstantiated()
    {
        new TestEnum;
    }
}

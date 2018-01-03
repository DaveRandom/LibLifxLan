<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Label;
use PHPUnit\Framework\TestCase;

final class LabelTest extends TestCase
{
    private $value;

    protected function setUp(): void
    {
        $this->value = \str_pad('', 32, 'test');
    }

    public function testValuePropertyValidValues(): void
    {
        $this->assertSame((new Label($this->value))->getValue(), $this->value);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPayloadPropertyValueTooLong(): void
    {
        new Label(\str_pad('', 33, 'test'));
    }

    public function testCanBeCastToString(): void
    {
        $this->assertSame((string)new Label($this->value), $this->value);
    }
}

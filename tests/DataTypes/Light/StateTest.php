<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\DataTypes\Light\State;
use PHPUnit\Framework\TestCase;

final class StateTest extends TestCase
{
    private $color;
    private $label;

    protected function setUp(): void
    {
        $this->color = new HsbkColor(0, 0, 0, 2500);
        $this->label = new Label('Test');
    }

    public function testColorProperty(): void
    {
        $this->assertSame((new State($this->color, 0, $this->label))->getColor(), $this->color);
    }

    public function testPowerPropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $level) {
            $this->assertSame((new State($this->color, $level, $this->label))->getPower(), $level);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPowerPropertyValueTooLow(): void
    {
        new State($this->color, -1, $this->label);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPowerPropertyValueTooHigh(): void
    {
        new State($this->color, 65536, $this->label);
    }

    public function testLabelProperty(): void
    {
        $this->assertSame((new State($this->color, 0, $this->label))->getLabel(), $this->label);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\PowerTransition;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetPower;
use PHPUnit\Framework\TestCase;

final class SetPowerTest extends TestCase
{
    private $transition;

    protected function setUp(): void
    {
        $this->transition = new PowerTransition(0, 0);
    }

    public function testColorTransitionProperty(): void
    {
        $this->assertSame((new SetPower($this->transition))->getPowerTransition(), $this->transition);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetPower($this->transition))->getTypeId(), SetPower::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetPower($this->transition))->getWireSize(), SetPower::WIRE_SIZE);
    }
}

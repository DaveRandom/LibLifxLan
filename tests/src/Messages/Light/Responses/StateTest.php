<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Responses;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\DataTypes\Light\State as LightState;
use DaveRandom\LibLifxLan\Messages\Light\Responses\State;
use PHPUnit\Framework\TestCase;

final class StateTest extends TestCase
{
    private $state;

    protected function setUp(): void
    {
        $this->state = new LightState(new HsbkColor(0, 0, 0, 2500), 0, new Label('Test'));
    }

    public function testStateProperty(): void
    {
        $this->assertSame((new State($this->state))->getState(), $this->state);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new State($this->state))->getTypeId(), State::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new State($this->state))->getWireSize(), State::WIRE_SIZE);
    }
}

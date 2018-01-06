<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use PHPUnit\Framework\TestCase;

final class SetColorTest extends TestCase
{
    private $transition;

    protected function setUp(): void
    {
        $this->transition = new ColorTransition(new HsbkColor(0, 0, 0, 2500), 0);
    }

    public function testColorTransitionProperty(): void
    {
        $this->assertSame((new SetColor($this->transition))->getColorTransition(), $this->transition);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetColor($this->transition))->getTypeId(), SetColor::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetColor($this->transition))->getWireSize(), SetColor::WIRE_SIZE);
    }
}

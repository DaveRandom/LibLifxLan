<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Messages\Device\Commands\SetLabel;
use PHPUnit\Framework\TestCase;

final class SetLabelTest extends TestCase
{
    private $label;

    protected function setUp(): void
    {
        $this->label = new Label('Test');
    }

    public function testLabelProperty(): void
    {
        $this->assertSame((new SetLabel($this->label))->getLabel(), $this->label);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetLabel($this->label))->getTypeId(), SetLabel::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetLabel($this->label))->getWireSize(), SetLabel::WIRE_SIZE);
    }
}

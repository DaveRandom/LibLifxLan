<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateLabel;
use PHPUnit\Framework\TestCase;

final class StateLabelTest extends TestCase
{
    private $label;

    protected function setUp(): void
    {
        $this->label = new Label('Test');
    }

    public function testLabelProperty(): void
    {
        $this->assertSame((new StateLabel($this->label))->getLabel(), $this->label);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateLabel($this->label))->getTypeId(), StateLabel::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateLabel($this->label))->getWireSize(), StateLabel::WIRE_SIZE);
    }
}

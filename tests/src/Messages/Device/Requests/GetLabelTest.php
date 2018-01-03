<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetLabel;
use PHPUnit\Framework\TestCase;

final class GetLabelTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetLabel)->getTypeId(), GetLabel::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetLabel)->getWireSize(), GetLabel::WIRE_SIZE);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetWifiFirmware;
use PHPUnit\Framework\TestCase;

final class GetWifiFirmwareTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetWifiFirmware)->getTypeId(), GetWifiFirmware::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetWifiFirmware)->getWireSize(), GetWifiFirmware::WIRE_SIZE);
    }
}

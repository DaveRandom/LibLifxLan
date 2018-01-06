<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetHostFirmware;
use PHPUnit\Framework\TestCase;

final class GetHostFirmwareTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetHostFirmware)->getTypeId(), GetHostFirmware::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetHostFirmware)->getWireSize(), GetHostFirmware::WIRE_SIZE);
    }
}

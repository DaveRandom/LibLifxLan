<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetWifiInfo;
use PHPUnit\Framework\TestCase;

final class GetWifiInfoTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetWifiInfo)->getTypeId(), GetWifiInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetWifiInfo)->getWireSize(), GetWifiInfo::WIRE_SIZE);
    }
}

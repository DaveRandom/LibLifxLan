<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\WifiInfo;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateWifiInfo;
use PHPUnit\Framework\TestCase;

final class StateWifiInfoTest extends TestCase
{
    private $info;

    protected function setUp(): void
    {
        $this->info = new WifiInfo(0.0, 0, 0);
    }

    public function testWifiInfoProperty(): void
    {
        $this->assertSame((new StateWifiInfo($this->info))->getWifiInfo(), $this->info);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateWifiInfo($this->info))->getTypeId(), StateWifiInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateWifiInfo($this->info))->getWireSize(), StateWifiInfo::WIRE_SIZE);
    }
}

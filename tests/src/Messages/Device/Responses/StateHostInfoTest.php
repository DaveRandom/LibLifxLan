<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\HostInfo;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateHostInfo;
use PHPUnit\Framework\TestCase;

final class StateHostInfoTest extends TestCase
{
    private $info;

    protected function setUp(): void
    {
        $this->info = new HostInfo(0.0, 0, 0);
    }

    public function testHostInfoProperty(): void
    {
        $this->assertSame((new StateHostInfo($this->info))->getHostInfo(), $this->info);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateHostInfo($this->info))->getTypeId(), StateHostInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateHostInfo($this->info))->getWireSize(), StateHostInfo::WIRE_SIZE);
    }
}

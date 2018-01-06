<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\TimeInfo;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateInfo;
use PHPUnit\Framework\TestCase;

final class StateInfoTest extends TestCase
{
    private $info;

    protected function setUp(): void
    {
        $this->info = new TimeInfo(new \DateTime, 0, 0);
    }

    public function testInfoProperty(): void
    {
        $this->assertSame((new StateInfo($this->info))->getInfo(), $this->info);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateInfo($this->info))->getTypeId(), StateInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateInfo($this->info))->getWireSize(), StateInfo::WIRE_SIZE);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\TimeInfo;
use PHPUnit\Framework\TestCase;

final class TimeInfoTest extends TestCase
{
    public function testTimeProperty(): void
    {
        $timestamp = new \DateTime;
        $this->assertSame((new TimeInfo($timestamp, 0, 0))->getTime()->format('u U'), $timestamp->format('u U'));
    }

    public function testUptimeProperty(): void
    {
        $this->assertSame((new TimeInfo(new \DateTime, 0, 0))->getUptime(), 0);
        $this->assertSame((new TimeInfo(new \DateTime, 12345, 0))->getUptime(), 12345);
    }

    public function testDowntimeProperty(): void
    {
        $this->assertSame((new TimeInfo(new \DateTime, 0, 0))->getDowntime(), 0);
        $this->assertSame((new TimeInfo(new \DateTime, 0, 12345))->getDowntime(), 12345);
    }
}

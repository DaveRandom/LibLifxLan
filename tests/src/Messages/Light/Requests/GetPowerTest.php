<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Requests;

use DaveRandom\LibLifxLan\Messages\Light\Requests\GetPower;
use PHPUnit\Framework\TestCase;

final class GetPowerTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetPower)->getTypeId(), GetPower::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetPower)->getWireSize(), GetPower::WIRE_SIZE);
    }
}

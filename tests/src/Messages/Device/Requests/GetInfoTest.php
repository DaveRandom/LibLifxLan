<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetInfo;
use PHPUnit\Framework\TestCase;

final class GetInfoTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetInfo)->getTypeId(), GetInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetInfo)->getWireSize(), GetInfo::WIRE_SIZE);
    }
}

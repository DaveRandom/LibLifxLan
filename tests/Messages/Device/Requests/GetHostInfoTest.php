<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetHostInfo;
use PHPUnit\Framework\TestCase;

final class GetHostInfoTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetHostInfo)->getTypeId(), GetHostInfo::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetHostInfo)->getWireSize(), GetHostInfo::WIRE_SIZE);
    }
}

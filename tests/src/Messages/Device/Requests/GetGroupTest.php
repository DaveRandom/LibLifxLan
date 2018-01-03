<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetGroup;
use PHPUnit\Framework\TestCase;

final class GetGroupTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetGroup)->getTypeId(), GetGroup::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetGroup)->getWireSize(), GetGroup::WIRE_SIZE);
    }
}

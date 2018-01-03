<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetLocation;
use PHPUnit\Framework\TestCase;

final class GetLocationTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetLocation)->getTypeId(), GetLocation::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetLocation)->getWireSize(), GetLocation::WIRE_SIZE);
    }
}

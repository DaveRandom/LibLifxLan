<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetService;
use PHPUnit\Framework\TestCase;

final class GetServiceTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetService)->getTypeId(), GetService::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetService)->getWireSize(), GetService::WIRE_SIZE);
    }
}

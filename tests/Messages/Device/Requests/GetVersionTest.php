<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Device\Requests\GetVersion;
use PHPUnit\Framework\TestCase;

final class GetVersionTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetVersion)->getTypeId(), GetVersion::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetVersion)->getWireSize(), GetVersion::WIRE_SIZE);
    }
}

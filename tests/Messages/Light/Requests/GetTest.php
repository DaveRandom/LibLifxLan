<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\Light\Requests\Get;
use PHPUnit\Framework\TestCase;

final class GetTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new Get)->getTypeId(), Get::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new Get)->getWireSize(), Get::WIRE_SIZE);
    }
}

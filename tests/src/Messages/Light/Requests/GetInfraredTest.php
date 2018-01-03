<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Light\Requests;

use DaveRandom\LibLifxLan\Messages\Light\Requests\GetInfrared;
use PHPUnit\Framework\TestCase;

final class GetInfraredTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new GetInfrared)->getTypeId(), GetInfrared::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new GetInfrared)->getWireSize(), GetInfrared::WIRE_SIZE);
    }
}

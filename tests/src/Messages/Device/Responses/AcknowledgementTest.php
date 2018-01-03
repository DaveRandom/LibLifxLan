<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\Messages\Device\Responses\Acknowledgement;
use PHPUnit\Framework\TestCase;

final class AcknowledgementTest extends TestCase
{
    public function testTypeIdProperty(): void
    {
        $this->assertSame((new Acknowledgement)->getTypeId(), Acknowledgement::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new Acknowledgement)->getWireSize(), Acknowledgement::WIRE_SIZE);
    }
}

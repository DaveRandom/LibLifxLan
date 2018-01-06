<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class HeaderTest extends TestCase
{
    private $frame;
    private $frameAddress;
    private $protocolHeader;

    protected function setUp(): void
    {
        $this->frame = new Frame(0, 0, false, false, 0, 0);
        $this->frameAddress = new FrameAddress(new MacAddress(1, 2, 3, 4, 5, 6), false, false, 0);
        $this->protocolHeader = new ProtocolHeader(0);
    }

    public function testFrameProperty(): void
    {
        $header = new Header($this->frame, $this->frameAddress, $this->protocolHeader);
        $this->assertSame($header->getFrame(), $this->frame);
    }

    public function testFrameAddressProperty(): void
    {
        $header = new Header($this->frame, $this->frameAddress, $this->protocolHeader);
        $this->assertSame($header->getFrameAddress(), $this->frameAddress);
    }

    public function testProtocolHeaderProperty(): void
    {
        $header = new Header($this->frame, $this->frameAddress, $this->protocolHeader);
        $this->assertSame($header->getProtocolHeader(), $this->protocolHeader);
    }
}

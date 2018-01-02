<?php
/**
 * Created by PhpStorm.
 * User: chris.wright
 * Date: 02/01/2018
 * Time: 16:40
 */

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\Network\MacAddress;
use PHPUnit\Framework\TestCase;

final class HeaderTest extends TestCase
{
    private function createFrame(): Frame
    {
        return new Frame(0, 0, false, false, 0, 0);
    }

    private function createFrameAddress(): FrameAddress
    {
        return new FrameAddress(new MacAddress(1, 2, 3, 4, 5, 6), false, false, 0);
    }

    private function createProtocolHeader(): ProtocolHeader
    {
        return new ProtocolHeader(0);
    }

    public function testFrameProperty(): void
    {
        $frame = $this->createFrame();
        $frameAddress = $this->createFrameAddress();
        $protocolHeader = $this->createProtocolHeader();

        $this->assertSame((new Header($frame, $frameAddress, $protocolHeader))->getFrame(), $frame);
    }

    public function testFrameAddressProperty(): void
    {
        $frame = $this->createFrame();
        $frameAddress = $this->createFrameAddress();
        $protocolHeader = $this->createProtocolHeader();

        $this->assertSame((new Header($frame, $frameAddress, $protocolHeader))->getFrameAddress(), $frameAddress);
    }

    public function testProtocolHeaderProperty(): void
    {
        $frame = $this->createFrame();
        $frameAddress = $this->createFrameAddress();
        $protocolHeader = $this->createProtocolHeader();

        $this->assertSame((new Header($frame, $frameAddress, $protocolHeader))->getProtocolHeader(), $protocolHeader);
    }
}

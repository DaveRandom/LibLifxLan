<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Header;

use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use PHPUnit\Framework\TestCase;

final class ProtocolHeaderTest extends TestCase
{
    public function testTypePropertyValidValues(): void
    {
        foreach ([0, 42, 65535] as $value) {
            $this->assertSame((new ProtocolHeader($value))->getType(), $value);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTypePropertyValueTooLow(): void
    {
        new ProtocolHeader(-1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTypePropertyValueTooHigh(): void
    {
        new ProtocolHeader(65536);
    }
}

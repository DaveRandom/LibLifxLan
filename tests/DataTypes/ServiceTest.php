<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Service;
use DaveRandom\LibLifxLan\DataTypes\ServiceTypes;
use PHPUnit\Framework\TestCase;

final class ServiceTest extends TestCase
{
    public function testTypeIdPropertyValidValues(): void
    {
        foreach ([0, 42, 255] as $typeId) {
            $this->assertSame((new Service($typeId, 0))->getTypeId(), $typeId);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTypeIdPropertyValueTooLow(): void
    {
        new Service(-1, 0);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testTypeIdPropertyValueTooHigh(): void
    {
        new Service(256, 0);
    }

    public function testPortPropertyValidValues(): void
    {
        foreach ([0, 42, 0xffffffff] as $port) {
            $this->assertSame((new Service(0, $port))->getPort(), $port);
        }
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPortPropertyValueTooLow(): void
    {
        new Service(0, 0 - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPortPropertyValueTooHigh(): void
    {
        new Service(0, 0xffffffff + 1);
    }

    public function testNamePropertyKnownService(): void
    {
        $this->assertSame((new Service(ServiceTypes::UDP, 0))->getName(), 'UDP');
    }

    public function testNamePropertyUnknownService(): void
    {
        $this->assertSame((new Service(255, 0))->getName(), 'Unknown(255)');
    }
}

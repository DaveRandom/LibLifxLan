<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes\x64;

use DaveRandom\LibLifxLan\DataTypes\Service;
use DaveRandom\LibLifxLan\Tests\x64Test;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class ServiceTest extends \DaveRandom\LibLifxLan\Tests\DataTypes\ServiceTest
{
    use x64Test;

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPortPropertyValueTooLow(): void
    {
        new Service(0, UINT32_MIN - 1);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testPortPropertyValueTooHigh(): void
    {
        new Service(0, UINT32_MAX + 1);
    }
}

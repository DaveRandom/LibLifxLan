<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\DataTypes\Location;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateLocation;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class StateLocationTest extends TestCase
{
    private $location;

    protected function setUp(): void
    {
        $this->location = new Location(Uuid::getFactory()->uuid4(), new Label('Test'), new \DateTime);
    }

    public function testLocationProperty(): void
    {
        $this->assertSame((new StateLocation($this->location))->getLocation(), $this->location);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateLocation($this->location))->getTypeId(), StateLocation::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateLocation($this->location))->getWireSize(), StateLocation::WIRE_SIZE);
    }
}

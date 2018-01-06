<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\DataTypes\Location;
use DaveRandom\LibLifxLan\Messages\Device\Commands\SetLocation;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SetLocationTest extends TestCase
{
    private $location;

    protected function setUp(): void
    {
        $this->location = new Location(Uuid::getFactory()->uuid4(), new Label('Test'), new \DateTime);
    }

    public function testLocationProperty(): void
    {
        $this->assertSame((new SetLocation($this->location))->getLocation(), $this->location);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetLocation($this->location))->getTypeId(), SetLocation::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetLocation($this->location))->getWireSize(), SetLocation::WIRE_SIZE);
    }
}

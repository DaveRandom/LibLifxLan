<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Commands;

use DaveRandom\LibLifxLan\DataTypes\Group;
use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Messages\Device\Commands\SetGroup;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class SetGroupTest extends TestCase
{
    private $group;

    protected function setUp(): void
    {
        $this->group = new Group(Uuid::getFactory()->uuid4(), new Label('Test'), new \DateTime);
    }

    public function testGroupProperty(): void
    {
        $this->assertSame((new SetGroup($this->group))->getGroup(), $this->group);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new SetGroup($this->group))->getTypeId(), SetGroup::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new SetGroup($this->group))->getWireSize(), SetGroup::WIRE_SIZE);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Group;
use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateGroup;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class StateGroupTest extends TestCase
{
    private $group;

    protected function setUp(): void
    {
        $this->group = new Group(Uuid::getFactory()->uuid4(), new Label('Test'), new \DateTime);
    }

    public function testGroupProperty(): void
    {
        $this->assertSame((new StateGroup($this->group))->getGroup(), $this->group);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateGroup($this->group))->getTypeId(), StateGroup::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateGroup($this->group))->getWireSize(), StateGroup::WIRE_SIZE);
    }
}

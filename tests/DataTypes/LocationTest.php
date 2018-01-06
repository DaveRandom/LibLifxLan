<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\DataTypes\Location;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class LocationTest extends TestCase
{
    private $guid;
    private $label;
    private $updatedAt;

    protected function setUp(): void
    {
        $this->guid = Uuid::getFactory()->uuid4();
        $this->label = new Label('Test');
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function testGuidProperty(): void
    {
        $this->assertSame((new Location($this->guid, $this->label, $this->updatedAt))->getGuid(), $this->guid);
    }

    public function testLabelProperty(): void
    {
        $this->assertSame((new Location($this->guid, $this->label, $this->updatedAt))->getLabel(), $this->label);
    }

    public function testUpdatedAtProperty(): void
    {
        $this->assertSame((new Location($this->guid, $this->label, $this->updatedAt))->getUpdatedAt(), $this->updatedAt);
    }
}

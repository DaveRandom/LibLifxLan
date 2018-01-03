<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Version;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateVersion;
use PHPUnit\Framework\TestCase;

final class StateVersionTest extends TestCase
{
    private $version;

    protected function setUp(): void
    {
        $this->version = new Version(0, 0, 0);
    }

    public function testVersionProperty(): void
    {
        $this->assertSame((new StateVersion($this->version))->getVersion(), $this->version);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateVersion($this->version))->getTypeId(), StateVersion::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateVersion($this->version))->getWireSize(), StateVersion::WIRE_SIZE);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Service;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateService;
use PHPUnit\Framework\TestCase;

final class StateServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new Service(0, 0);
    }

    public function testServiceProperty(): void
    {
        $this->assertSame((new StateService($this->service))->getService(), $this->service);
    }

    public function testTypeIdProperty(): void
    {
        $this->assertSame((new StateService($this->service))->getTypeId(), StateService::MESSAGE_TYPE_ID);
    }

    public function testWireSizeProperty(): void
    {
        $this->assertSame((new StateService($this->service))->getWireSize(), StateService::WIRE_SIZE);
    }
}

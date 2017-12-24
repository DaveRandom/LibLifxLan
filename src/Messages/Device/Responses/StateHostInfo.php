<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\HostInfo;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateHostInfo extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 12;

    private $hostInfo;

    public function __construct(HostInfo $hostInfo)
    {
        parent::__construct();

        $this->hostInfo = $hostInfo;
    }

    public function getHostInfo(): HostInfo
    {
        return $this->hostInfo;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

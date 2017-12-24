<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\HostFirmware;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateHostFirmware extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 15;

    private $hostFirmware;

    public function __construct(HostFirmware $hostFirmware)
    {
        parent::__construct();

        $this->hostFirmware = $hostFirmware;
    }

    public function getHostFirmware(): HostFirmware
    {
        return $this->hostFirmware;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

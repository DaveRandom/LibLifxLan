<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\Service;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateService extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 3;
    public const PAYLOAD_SIZE = 5;

    private $service;

    public function __construct(Service $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

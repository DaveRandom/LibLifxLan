<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Requests;

use DaveRandom\LifxLan\Messages\RequestMessage;

final class GetPower extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 20;
    public const PAYLOAD_SIZE = 0;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Requests;

use DaveRandom\LifxLan\Messages\RequestMessage;

final class GetPower extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 116;
    public const PAYLOAD_SIZE = 0;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

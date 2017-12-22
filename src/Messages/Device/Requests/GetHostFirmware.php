<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Requests;

use DaveRandom\LifxLan\Messages\RequestMessage;

final class GetHostFirmware extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 14;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

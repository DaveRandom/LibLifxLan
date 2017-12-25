<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\RequestMessage;

final class GetService extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 2;
    public const PAYLOAD_SIZE = 0;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

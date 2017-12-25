<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class Acknowledgement extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 45;
    public const PAYLOAD_SIZE = 0;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

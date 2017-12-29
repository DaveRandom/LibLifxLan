<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\RequestMessage;

final class GetWifiInfo extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 16;
    public const WIRE_SIZE = 0;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    public function getWireSize(): int
    {
        return self::WIRE_SIZE;
    }
}

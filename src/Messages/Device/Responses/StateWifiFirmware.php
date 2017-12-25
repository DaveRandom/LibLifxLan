<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\WifiFirmware;
use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class StateWifiFirmware extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 19;
    public const PAYLOAD_SIZE = 20;

    private $wifiFirmware;

    public function __construct(WifiFirmware $wifiFirmware)
    {
        parent::__construct();

        $this->wifiFirmware = $wifiFirmware;
    }

    public function getWifiFirmware(): WifiFirmware
    {
        return $this->wifiFirmware;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

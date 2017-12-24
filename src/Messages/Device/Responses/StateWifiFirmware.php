<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\WifiFirmware;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateWifiFirmware extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 19;

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

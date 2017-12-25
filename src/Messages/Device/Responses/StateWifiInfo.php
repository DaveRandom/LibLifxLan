<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\WifiInfo;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateWifiInfo extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 17;
    public const PAYLOAD_SIZE = 14;

    private $wifiInfo;

    public function __construct(WifiInfo $wifiInfo)
    {
        parent::__construct();

        $this->wifiInfo = $wifiInfo;
    }

    public function getWifiInfo(): WifiInfo
    {
        return $this->wifiInfo;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

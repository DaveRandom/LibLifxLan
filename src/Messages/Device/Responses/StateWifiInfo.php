<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\WifiInfo;
use DaveRandom\LibLifxLan\Messages\ResponseMessage;

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

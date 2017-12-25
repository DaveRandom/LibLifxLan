<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\DataTypes\Info;
use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class StateInfo extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 35;
    public const PAYLOAD_SIZE = 24;

    private $info;

    public function __construct(Info $info)
    {
        parent::__construct();
        $this->info = $info;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

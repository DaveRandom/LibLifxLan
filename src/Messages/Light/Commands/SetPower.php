<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Light\Commands;

use DaveRandom\LibLifxLan\DataTypes\Light\PowerTransition;
use DaveRandom\LibLifxLan\Messages\CommandMessage;

final class SetPower extends CommandMessage
{
    public const MESSAGE_TYPE_ID = 117;
    public const PAYLOAD_SIZE = 6;

    private $powerTransition;

    public function __construct(PowerTransition $powerTransition)
    {
        parent::__construct();

        $this->powerTransition = $powerTransition;
    }

    public function getPowerTransition(): PowerTransition
    {
        return $this->powerTransition;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

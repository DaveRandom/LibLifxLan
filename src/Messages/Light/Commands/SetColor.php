<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Commands;

use DaveRandom\LifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LifxLan\Messages\CommandMessage;

final class SetColor extends CommandMessage
{
    public const MESSAGE_TYPE_ID = 102;
    public const PAYLOAD_SIZE = 13;

    private $colorTransition;

    public function __construct(ColorTransition $colorTransition)
    {
        parent::__construct();

        $this->colorTransition = $colorTransition;
    }

    public function getColorTransition(): ColorTransition
    {
        return $this->colorTransition;
    }
    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

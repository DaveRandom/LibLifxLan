<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Instructions;

use DaveRandom\LifxLan\HsbkColor;
use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetColor extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 102;

    private $color;
    private $transitionDuration;

    public function __construct(HsbkColor $color, int $transitionDuration)
    {
        $this->color = $color;
        $this->transitionDuration = $transitionDuration;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getTransitionDuration(): int
    {
        return $this->transitionDuration;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

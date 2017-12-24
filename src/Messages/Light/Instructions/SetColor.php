<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Instructions;

use DaveRandom\LifxLan\DataTypes\ColorTransition;
use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetColor extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 102;

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

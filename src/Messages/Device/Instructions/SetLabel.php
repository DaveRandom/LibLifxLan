<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Instructions;

use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetLabel extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 24;

    private $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

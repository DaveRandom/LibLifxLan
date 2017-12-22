<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Light\Instructions;

use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetInfrared extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 121;

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }

    // todo
}

<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages;

abstract class InstructionMessage extends Message
{
    public function isAckRequired(): bool
    {
        return true;
    }
}

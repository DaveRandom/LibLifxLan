<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages;

abstract class Message
{
    abstract public function getTypeId(): int;

    public function isAckRequired(): bool
    {
        return false;
    }

    public function isResponseRequired(): bool
    {
        return false;
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages;

abstract class RequestMessage extends Message
{
    public function isResponseRequired(): bool
    {
        return true;
    }
}

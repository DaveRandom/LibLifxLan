<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages;

abstract class InstructionMessage extends Message
{
    public function __construct(int $responsePattern = self::REQUIRE_ACK)
    {
        parent::__construct($responsePattern);
    }
}

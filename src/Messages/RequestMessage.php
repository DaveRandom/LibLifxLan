<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages;

abstract class RequestMessage extends Message
{
    public function __construct(int $responsePattern = self::REQUIRE_RESPONSE)
    {
        parent::__construct($responsePattern);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages;

abstract class ResponseMessage extends Message
{
    public function __construct(int $responsePattern = 0)
    {
        parent::__construct($responsePattern);
    }
}

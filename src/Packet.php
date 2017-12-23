<?php declare(strict_types=1);

namespace DaveRandom\LifxLan;

use DaveRandom\LifxLan\Header\Header;
use DaveRandom\LifxLan\Messages\Message;

final class Packet
{
    private $header;
    private $message;

    public function __construct(Header $header, Message $message)
    {
        $this->header = $header;
        $this->message = $message;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}

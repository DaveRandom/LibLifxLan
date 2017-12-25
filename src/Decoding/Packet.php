<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\Network\IPEndpoint;

final class Packet
{
    private $header;
    private $message;
    private $source;

    public function __construct(Header $header, Message $message, IPEndpoint $source)
    {
        $this->header = $header;
        $this->message = $message;
        $this->source = $source;
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getSource(): IPEndpoint
    {
        return $this->source;
    }
}

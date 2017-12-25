<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Responses;

use DaveRandom\LibLifxLan\Messages\ResponseMessage;

final class EchoResponse extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 59;
    public const PAYLOAD_SIZE = 64;

    private $payload;

    public function __construct(string $payload)
    {
        parent::__construct();

        $this->payload = $payload;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

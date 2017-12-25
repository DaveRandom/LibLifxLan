<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Requests;

use DaveRandom\LibLifxLan\Messages\RequestMessage;

final class EchoRequest extends RequestMessage
{
    public const MESSAGE_TYPE_ID = 58;
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

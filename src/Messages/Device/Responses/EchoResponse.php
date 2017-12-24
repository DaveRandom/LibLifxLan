<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\Messages\ResponseMessage;

final class EchoResponse extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 59;

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

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages\Device\Commands;

use DaveRandom\LibLifxLan\Messages\CommandMessage;

final class SetLabel extends CommandMessage
{
    public const MESSAGE_TYPE_ID = 24;
    public const PAYLOAD_SIZE = 32;

    private $label;

    public function __construct(string $label)
    {
        parent::__construct();

        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

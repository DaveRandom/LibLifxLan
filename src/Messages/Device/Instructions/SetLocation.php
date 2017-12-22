<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Instructions;

use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetLocation extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 49;

    private $locationGuid;
    private $label;
    private $updatedAt;

    public function __construct(string $locationGuid, string $label, int $updatedAt = null)
    {
        $this->locationGuid = $locationGuid;
        $this->label = $label;
        $this->updatedAt = $updatedAt ?? (int)(\microtime(true) * 1e9);
    }

    public function getLocationGuid(): string
    {
        return $this->locationGuid;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

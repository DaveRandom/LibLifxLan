<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Instructions;

use DaveRandom\LifxLan\Messages\InstructionMessage;

final class SetGroup extends InstructionMessage
{
    public const MESSAGE_TYPE_ID = 52;

    private $groupGuid;
    private $label;
    private $updatedAt;

    public function __construct(string $groupGuid, string $label, int $updatedAt = null)
    {
        $this->groupGuid = $groupGuid;
        $this->label = $label;
        $this->updatedAt = $updatedAt ?? (int)(\microtime(true) * 1e9);
    }

    public function getGroupGuid(): string
    {
        return $this->groupGuid;
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

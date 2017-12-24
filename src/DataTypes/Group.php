<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

final class Group
{
    private $guid;
    private $label;
    private $updatedAt;

    public function __construct(string $guid, string $label, int $updatedAt = null)
    {
        $this->guid = $guid;
        $this->label = $label;
        $this->updatedAt = $updatedAt ?? (int)(\microtime(true) * 1e9);
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }
}

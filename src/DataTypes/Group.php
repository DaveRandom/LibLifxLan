<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\DataTypes;

use Ramsey\Uuid\UuidInterface;

final class Group
{
    private $guid;
    private $label;
    private $updatedAt;

    public function __construct(UuidInterface $guid, string $label, int $updatedAt = null)
    {
        $this->guid = $guid;
        $this->label = $label;
        $this->updatedAt = $updatedAt ?? (int)(\microtime(true) * 1e9);
    }

    public function getGuid(): UuidInterface
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

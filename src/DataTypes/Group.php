<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use Ramsey\Uuid\UuidInterface;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class Group
{
    private $guid;
    private $label;
    private $updatedAt;

    public function __construct(UuidInterface $guid, string $label, \DateTimeInterface $updatedAt)
    {
        $this->guid = $guid;
        $this->label = $label;
        $this->updatedAt = datetimeinterface_to_datetimeimmutable($updatedAt);
    }

    public function getGuid(): UuidInterface
    {
        return $this->guid;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

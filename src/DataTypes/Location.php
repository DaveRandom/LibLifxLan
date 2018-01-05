<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use Ramsey\Uuid\UuidInterface;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class Location
{
    private $guid;
    private $label;
    private $updatedAt;

    /**
     * @param UuidInterface $guid
     * @param Label $label
     * @param \DateTimeInterface $updatedAt
     */
    public function __construct(UuidInterface $guid, Label $label, \DateTimeInterface $updatedAt)
    {
        $this->guid = $guid;
        $this->label = $label;
        $this->updatedAt = datetimeinterface_to_datetimeimmutable($updatedAt);
    }

    public function getGuid(): UuidInterface
    {
        return $this->guid;
    }

    public function getLabel(): Label
    {
        return $this->label;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

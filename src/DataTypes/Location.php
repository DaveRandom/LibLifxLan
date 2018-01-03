<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use Ramsey\Uuid\UuidInterface;
use function DaveRandom\LibLifxLan\datetimeinterface_to_datetimeimmutable;

final class Location
{
    private $guid;
    private $label;
    private $updatedAt;

    private function setGuid(UuidInterface $guid): void
    {
        $this->guid = $guid;
    }

    private function setLabel(Label $label): void
    {
        $this->label = $label;
    }

    private function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param UuidInterface $guid
     * @param Label $label
     * @param \DateTimeInterface $updatedAt
     */
    public function __construct(UuidInterface $guid, Label $label, \DateTimeInterface $updatedAt)
    {
        $this->setGuid($guid);
        $this->setLabel($label);
        $this->setUpdatedAt(datetimeinterface_to_datetimeimmutable($updatedAt));
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

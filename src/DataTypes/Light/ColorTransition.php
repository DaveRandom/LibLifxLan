<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class ColorTransition
{
    private $color;
    private $duration;

    /**
     * @param HsbkColor $color
     * @param int $duration
     * @throws InvalidValueException
     */
    public function __construct(HsbkColor $color, int $duration)
    {
        if ($duration < UINT32_MIN || $duration > UINT32_MAX) {
            throw new InvalidValueException(
                "Transition duration {$duration} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->color = $color;
        $this->duration = $duration;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}

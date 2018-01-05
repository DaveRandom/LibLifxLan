<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint32;

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
        $this->color = $color;
        $this->duration = validate_uint32('Transition duration', $duration);
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

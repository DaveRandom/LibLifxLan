<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint16;

final class State
{
    private $color;
    private $power;
    private $label;

    /**
     * @param HsbkColor $color
     * @param int $power
     * @param Label $label
     * @throws InvalidValueException
     */
    public function __construct(HsbkColor $color, int $power, Label $label)
    {
        $this->color = $color;
        $this->power = validate_uint16('Power level', $power);
        $this->label = $label;
    }

    public function getColor(): HsbkColor
    {
        return $this->color;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function getLabel(): Label
    {
        return $this->label;
    }
}

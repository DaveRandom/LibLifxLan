<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

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
        if ($power < 0 || $power > 65535) {
            throw new InvalidValueException("Power level {$power} outside allowable range of 0 - 65535");
        }

        $this->color = $color;
        $this->power = $power;
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

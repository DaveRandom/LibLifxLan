<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\DataTypes\Label;
use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class State
{
    private $color;
    private $power;
    private $label;

    private function setColor(HsbkColor $color): void
    {
        $this->color = $color;
    }

    /**
     * @param int $power
     * @throws InvalidValueException
     */
    private function setPower(int $power): void
    {
        if ($power < 0 || $power > 65535) {
            throw new InvalidValueException("Power level {$power} outside allowable range of 0 - 65535");
        }

        $this->power = $power;
    }

    private function setLabel(Label $label): void
    {
        $this->label = $label;
    }

    /**
     * @param HsbkColor $color
     * @param int $power
     * @param Label $label
     * @throws InvalidValueException
     */
    public function __construct(HsbkColor $color, int $power, Label $label)
    {
        $this->setColor($color);
        $this->setPower($power);
        $this->setLabel($label);
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

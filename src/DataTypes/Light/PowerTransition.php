<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class PowerTransition
{
    private $level;
    private $duration;

    /**
     * @param int $level
     * @param int $duration
     * @throws InvalidValueException
     */
    public function __construct(int $level, int $duration)
    {
        if ($level < 0 || $level > 65535) {
            throw new InvalidValueException("Power level {$level} outside allowable range of 0 - 65535");
        }

        if ($duration < UINT32_MIN || $duration > UINT32_MAX) {
            throw new InvalidValueException(
                "Transition duration {$duration} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->level = $level;
        $this->duration = $duration;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint16;
use function DaveRandom\LibLifxLan\validate_uint32;

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
        $this->level = validate_uint16('Power level', $level);
        $this->duration = validate_uint32('Transition duration', $duration);
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

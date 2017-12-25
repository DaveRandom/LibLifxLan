<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes\Light;

use DaveRandom\LibLifxLan\Enum;

final class Waveforms extends Enum
{
    public const SAW = 0;
    public const SINE = 1;
    public const HALF_SINE = 2;
    public const TRIANGLE = 3;
    public const PULSE = 4;
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Enum;

final class ProductFeatures extends Enum
{
    public const COLOR     = 0b001;
    public const INFRARED  = 0b010;
    public const MULTIZONE = 0b100;
}

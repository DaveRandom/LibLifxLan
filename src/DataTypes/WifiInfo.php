<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

final class WifiInfo extends NetworkInfo
{
    public function __construct(float $signal, int $tx, int $rx)
    {
        parent::__construct($signal, $tx, $rx);
    }
}

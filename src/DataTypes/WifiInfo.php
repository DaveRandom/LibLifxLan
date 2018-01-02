<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class WifiInfo extends NetworkInfo
{
    /**
     * @param float $signal
     * @param int $tx
     * @param int $rx
     * @throws InvalidValueException
     */
    public function __construct(float $signal, int $tx, int $rx)
    {
        parent::__construct($signal, $tx, $rx);
    }
}

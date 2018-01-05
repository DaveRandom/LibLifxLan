<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint32;

abstract class NetworkInfo
{
    private $signal;
    private $tx;
    private $rx;

    /**
     * @param float $signal
     * @param int $tx
     * @param int $rx
     * @throws InvalidValueException
     */
    protected function __construct(float $signal, int $tx, int $rx)
    {
        $this->signal = $signal;
        $this->tx = validate_uint32('Transmitted bytes', $tx);
        $this->rx = validate_uint32('Recieved bytes', $rx);
    }

    public function getSignal(): float
    {
        return $this->signal;
    }

    public function getTx(): int
    {
        return $this->tx;
    }

    public function getRx(): int
    {
        return $this->rx;
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

abstract class NetworkInfo
{
    private $signal;
    private $tx;
    private $rx;

    /**
     * @param int $tx
     * @throws InvalidValueException
     */
    private function setTx(int $tx): void
    {
        if ($tx < UINT32_MIN || $tx > UINT32_MAX) {
            throw new InvalidValueException(
                "Transmitted bytes {$tx} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->tx = $tx;
    }

    /**
     * @param int $rx
     * @throws InvalidValueException
     */
    private function setRx(int $rx): void
    {
        if ($rx < UINT32_MIN || $rx > UINT32_MAX) {
            throw new InvalidValueException(
                "Recieved bytes {$rx} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->rx = $rx;
    }

    /**
     * @param float $signal
     * @param int $tx
     * @param int $rx
     * @throws InvalidValueException
     */
    protected function __construct(float $signal, int $tx, int $rx)
    {
        $this->signal = $signal;
        $this->setTx($tx);
        $this->setRx($rx);
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

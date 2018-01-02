<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use const DaveRandom\LibLifxLan\UINT32_MAX;
use const DaveRandom\LibLifxLan\UINT32_MIN;

final class Version
{
    private $vendor;
    private $product;
    private $version;

    /**
     * @param int $vendor
     * @param int $product
     * @param int $version
     * @throws InvalidValueException
     */
    public function __construct(int $vendor, int $product, int $version)
    {
        if ($vendor < UINT32_MIN || $vendor > UINT32_MAX) {
            throw new InvalidValueException(
                "Vendor ID {$vendor} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        if ($product < UINT32_MIN || $product > UINT32_MAX) {
            throw new InvalidValueException(
                "Product ID {$product} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        if ($version < UINT32_MIN || $version > UINT32_MAX) {
            throw new InvalidValueException(
                "Product version {$version} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->vendor = $vendor;
        $this->product = $product;
        $this->version = $version;
    }

    public function getVendor(): int
    {
        return $this->vendor;
    }

    public function getProduct(): int
    {
        return $this->product;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}

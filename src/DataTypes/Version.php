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
     * @throws InvalidValueException
     */
    private function setVendor(int $vendor): void
    {
        if ($vendor < UINT32_MIN || $vendor > UINT32_MAX) {
            throw new InvalidValueException(
                "Vendor ID {$vendor} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->vendor = $vendor;
    }

    /**
     * @param int $product
     * @throws InvalidValueException
     */
    private function setProduct(int $product): void
    {
        if ($product < UINT32_MIN || $product > UINT32_MAX) {
            throw new InvalidValueException(
                "Product ID {$product} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->product = $product;
    }

    /**
     * @param int $version
     * @throws InvalidValueException
     */
    private function setVersion(int $version): void
    {
        if ($version < UINT32_MIN || $version > UINT32_MAX) {
            throw new InvalidValueException(
                "Product version {$version} outside allowable range of " . UINT32_MIN . " - " . UINT32_MAX
            );
        }

        $this->version = $version;
    }

    /**
     * @param int $vendor
     * @param int $product
     * @param int $version
     * @throws InvalidValueException
     */
    public function __construct(int $vendor, int $product, int $version)
    {
        $this->setVendor($vendor);
        $this->setProduct($product);
        $this->setVersion($version);
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

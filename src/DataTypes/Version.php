<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_uint32;

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
        $this->vendor = validate_uint32('Vendor ID', $vendor);
        $this->product = validate_uint32('Product ID', $product);
        $this->version = validate_uint32('Product version', $version);
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

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

final class Version
{
    private $vendor;
    private $product;
    private $version;

    public function __construct(int $vendor, int $product, int $version)
    {
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

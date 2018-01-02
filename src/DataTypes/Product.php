<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class Product
{
    private const PRODUCT_DATA = [
        '1:1' => ['Original 1000', ProductFeatures::COLOR],
        '1:3' => ['Color 650', ProductFeatures::COLOR],
        '1:10' => ['White 800 (Low Voltage)', 0],
        '1:11' => ['White 800 (High Voltage)', 0],
        '1:18' => ['White 900 BR30 (Low Voltage)', 0],
        '1:20' => ['Color 1000 BR30', ProductFeatures::COLOR],
        '1:22' => ['Color 1000', ProductFeatures::COLOR],
        '1:27' => ['LIFX A19', ProductFeatures::COLOR],
        '1:28' => ['LIFX BR30', ProductFeatures::COLOR],
        '1:29' => ['LIFX+ A19', ProductFeatures::COLOR | ProductFeatures::INFRARED],
        '1:30' => ['LIFX+ BR30', ProductFeatures::COLOR | ProductFeatures::INFRARED],
        '1:31' => ['LIFX Z', ProductFeatures::COLOR | ProductFeatures::MULTIZONE],
        '1:32' => ['LIFX Z 2', ProductFeatures::COLOR | ProductFeatures::MULTIZONE],
        '1:36' => ['LIFX Downlight', ProductFeatures::COLOR],
        '1:37' => ['LIFX Downlight', ProductFeatures::COLOR],
        '1:43' => ['LIFX A19', ProductFeatures::COLOR],
        '1:44' => ['LIFX BR30', ProductFeatures::COLOR],
        '1:45' => ['LIFX+ A19', ProductFeatures::COLOR | ProductFeatures::INFRARED],
        '1:46' => ['LIFX+ BR30', ProductFeatures::COLOR | ProductFeatures::INFRARED],
        '1:49' => ['LIFX Mini', ProductFeatures::COLOR],
        '1:50' => ['LIFX Mini Day and Dusk', 0],
        '1:51' => ['LIFX Mini White', 0],
        '1:52' => ['LIFX GU10', ProductFeatures::COLOR],
    ];

    private $version;
    private $name;
    private $features;

    /**
     * @param Version $version
     * @return Product
     * @throws InvalidValueException
     */
    public static function createFromVersion(Version $version): Product
    {
        $key = "{$version->getVendor()}:{$version->getProduct()}";

        if (!\array_key_exists($key, self::PRODUCT_DATA)) {
            throw new InvalidValueException("Unknown product: vid={$version->getVendor()};pid={$version->getProduct()}");
        }

        [$name, $features] = self::PRODUCT_DATA[$key];

        return new Product($version, $name, $features);
    }

    private function setVersion(Version $version): void
    {
        $this->version = $version;
    }

    private function setName(string $name): void
    {
        $this->name = $name;
    }

    private function setFeatures(int $features): void
    {
        $this->features = $features;
    }

    private function __construct(Version $version, string $name, int $features)
    {
        $this->setVersion($version);
        $this->setName($name);
        $this->setFeatures($features);
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFeatures(): int
    {
        return $this->features;
    }

    public function hasFeature(int $featureId): bool
    {
        return (bool)($this->features & $featureId);
    }
}

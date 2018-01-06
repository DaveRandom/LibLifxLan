<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\DataTypes;

use DaveRandom\LibLifxLan\DataTypes\Product;
use DaveRandom\LibLifxLan\DataTypes\ProductFeatures;
use DaveRandom\LibLifxLan\DataTypes\Version;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    /**
     * @throws \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testCreateFromKnownVersionSucceeds(): void
    {
        $this->assertInstanceOf(Product::class, Product::createFromVersion(new Version(1, 1, 0)));
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Exceptions\InvalidValueException
     */
    public function testCreateFromUnknownVersionFails(): void
    {
        Product::createFromVersion(new Version(0, 0, 0));
    }

    public function testVersionProperty(): void
    {
        $version = new Version(0, 0, 0);
        $this->assertSame((new Product($version, '', 0))->getVersion(), $version);
    }

    public function testNameProperty(): void
    {
        $this->assertSame((new Product(new Version(0, 0, 0), 'Test', 0))->getName(), 'Test');
    }

    public function testFeaturesProperty(): void
    {
        $this->assertSame((new Product(new Version(0, 0, 0), '', 1234))->getFeatures(), 1234);
    }

    public function testHasFeature(): void
    {
        $product = new Product(new Version(0, 0, 0), '', 0);
        $this->assertFalse($product->hasFeature(ProductFeatures::COLOR));
        $this->assertFalse($product->hasFeature(ProductFeatures::INFRARED));
        $this->assertFalse($product->hasFeature(ProductFeatures::MULTIZONE));

        $product = new Product(new Version(0, 0, 0), '', ProductFeatures::COLOR);
        $this->assertTrue($product->hasFeature(ProductFeatures::COLOR));
        $this->assertFalse($product->hasFeature(ProductFeatures::INFRARED));
        $this->assertFalse($product->hasFeature(ProductFeatures::MULTIZONE));

        $product = new Product(new Version(0, 0, 0), '', ProductFeatures::INFRARED);
        $this->assertFalse($product->hasFeature(ProductFeatures::COLOR));
        $this->assertTrue($product->hasFeature(ProductFeatures::INFRARED));
        $this->assertFalse($product->hasFeature(ProductFeatures::MULTIZONE));

        $product = new Product(new Version(0, 0, 0), '', ProductFeatures::MULTIZONE);
        $this->assertFalse($product->hasFeature(ProductFeatures::COLOR));
        $this->assertFalse($product->hasFeature(ProductFeatures::INFRARED));
        $this->assertTrue($product->hasFeature(ProductFeatures::MULTIZONE));

        $product = new Product(new Version(0, 0, 0), '', ProductFeatures::COLOR | ProductFeatures::INFRARED | ProductFeatures::MULTIZONE);
        $this->assertTrue($product->hasFeature(ProductFeatures::COLOR));
        $this->assertTrue($product->hasFeature(ProductFeatures::INFRARED));
        $this->assertTrue($product->hasFeature(ProductFeatures::MULTIZONE));
    }
}

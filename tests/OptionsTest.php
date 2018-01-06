<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

use DaveRandom\LibLifxLan\Options;
use PHPUnit\Framework\TestCase;

final class OptionsTest extends TestCase
{
    public function testGetOptionSetInConstructor(): void
    {
        $options = new class([1 => 'one']) { use Options; };

        $this->assertSame($options->getOption(1), 'one');
    }

    public function testGetUndefinedOption(): void
    {
        $options = new class { use Options; };

        $this->assertNull($options->getOption(1));
    }

    public function testGetOptionSetWithSetOption(): void
    {
        $options = new class { use Options; };
        $options->setOption(1, 'one');

        $this->assertSame($options->getOption(1), 'one');
    }
}

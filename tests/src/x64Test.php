<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests;

/**
 * @method void markTestSkipped(string $message)
 */
trait x64Test
{
    function setUp(): void
    {
        if (\PHP_INT_SIZE === 4) {
            $this->markTestSkipped('64-bit platforms only');
        }

        parent::setUp();
    }
}

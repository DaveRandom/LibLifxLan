<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\WireData;

final class FrameWireData
{
    public const DEFAULT_SIZE = 0;
    public const DEFAULT_ORIGIN = 0;

    public const DEFAULT_TAGGED_FLAG = false;
    public const DEFAULT_ADDRESSABLE_FLAG = false;

    public const DEFAULT_PROTOCOL_NUMBER = 0;
    public const DEFAULT_SOURCE = 0;

    public const VALID_SIZE_DATA = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => 0,
        "\xff\x00\x00\x00\x00\x00\x00\x00" => 0x00ff,
        "\x00\xff\x00\x00\x00\x00\x00\x00" => 0xff00,
    ];

    public const VALID_ORIGIN_DATA = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => 0,
        "\x00\x00\x00\x40\x00\x00\x00\x00" => 1,
        "\x00\x00\x00\x80\x00\x00\x00\x00" => 2,
        "\x00\x00\x00\xC0\x00\x00\x00\x00" => 3,
    ];

    public const VALID_FLAGS_DATA = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => ['tag' => false, 'add' => false],
        "\x00\x00\x00\x10\x00\x00\x00\x00" => ['tag' => false, 'add' => true],
        "\x00\x00\x00\x20\x00\x00\x00\x00" => ['tag' => true, 'add' => false],
        "\x00\x00\x00\x30\x00\x00\x00\x00" => ['tag' => true, 'add' => true],
    ];

    public const VALID_PROTOCOL_DATA = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => 0,
        "\x00\x00\xff\x00\x00\x00\x00\x00" => 0x00ff,
        "\x00\x00\x00\x0f\x00\x00\x00\x00" => 0x0f00,
    ];

    public const VALID_SOURCE_DATA = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => 0,
        "\x00\x00\x00\x00\x04\x03\x02\x01" => 0x01020304,
        "\x00\x00\x00\x00\x01\x02\x03\x04" => 0x04030201,
    ];

    public const INVALID_SHORT_DATA = "\x00\x00\x00\x00\x00\x00\x00";
}

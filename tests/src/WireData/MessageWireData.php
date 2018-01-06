<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\WireData;

use DaveRandom\LibLifxLan\DataTypes\Light\Effect;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;

final class MessageWireData
{
    public const DATETIME_FORMAT = 'Y-m-d H:i:s.u';
    public const DATETIME_VALUE = '2018-01-03 16:55:42.053987';
    public const DATETIME_BYTES = "\xb8\xd2\x5c\xae\x25\x5b\x06\x15";

    public const UUID_BYTES = "\x00\x01\x02\x02\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f";

    public const LABEL_VALUE = 'Test';
    public const LABEL_BYTES = "\x54\x65\x73\x74\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";

    public const UINT8_TEST_VALUES = [
        "\x00" => 0x00,
        "\x2a" => 0x2a,
        "\xff" => 0xff,
    ];

    public const INT16_TEST_VALUES = [
        "\x00\x80" => -0x8000,
        "\x00\x00" => 0x0000,
        "\x2a\x00" => 0x002a,
        "\xff\x7f" => 0x7fff,
    ];

    public const UINT16_TEST_VALUES = [
        "\x00\x00" => 0x0000,
        "\x2a\x00" => 0x002a,
        "\xff\x7f" => 0x7fff,
        "\xff\xff" => 0xffff,
    ];

    public const UINT32_TEST_VALUES = [
        "\x00\x00\x00\x00" => 0x00000000,
        "\x2a\x00\x00\x00" => 0x0000002a,
        "\x78\x56\x34\x12" => 0x12345678,
        "\xff\xff\xff\xff" => 0xffffffff,
    ];

    public const UINT64_TEST_VALUES = [
        "\x00\x00\x00\x00\x00\x00\x00\x00" => 0x0000000000000000,
        "\x2a\x00\x00\x00\x00\x00\x00\x00" => 0x000000000000002a,
        "\xf0\xde\xbc\x9a\x78\x56\x34\x12" => 0x123456789abcdef0,
        "\xff\xff\xff\xff\xff\xff\xff\x7f" => 0x7fffffffffffffff,
    ];

    public const FLOAT32_TEST_VALUES = [
        "\x00\x00\x00\x00" => 0.0,
        "\x00\x00\x00\x80" => -0.0,
        "\xdb\x0f\x49\x40" => 3.1415926535897968907,
    ];

    private const COLOR_TEMPERATURE_TEST_VALUES = [
        "\xc4\x09" => 2500,
        "\x94\x11" => 4500,
        "\x4c\x1d" => 7500,
        "\x1c\x25" => 9500,
    ];

    private const BOOL_TEST_VALUES = [
        "\x00" => false,
        "\x01" => true,
    ];

    /**
     * @return \Generator|HsbkColor[]
     */
    public static function generateTestHsbkColors(): \Generator
    {
        foreach (self::UINT16_TEST_VALUES as $hBytes => $h) {
            foreach (self::UINT16_TEST_VALUES as $sBytes => $s) {
                foreach (self::UINT16_TEST_VALUES as $bBytes => $b) {
                    foreach (self::COLOR_TEMPERATURE_TEST_VALUES as $kBytes => $k) {
                        $color = new HsbkColor($h, $s, $b, $k);
                        $data = $hBytes . $sBytes . $bBytes . $kBytes;

                        yield $data => $color;
                    }
                }
            }
        }
    }

    private static function generateTestEffectsWithOptions($data, $transient, $color, $period, $cycles, $skew, $waveform): \Generator
    {
        foreach (self::BOOL_TEST_VALUES as $setHBytes => $setH) {
            foreach (self::BOOL_TEST_VALUES as $setSBytes => $setS) {
                foreach (self::BOOL_TEST_VALUES as $setBBytes => $setB) {
                    foreach (self::BOOL_TEST_VALUES as $setKBytes => $setK) {
                        $options = 0;

                        if ($setH) {
                            $options |= Effect::SET_HUE;
                        }

                        if ($setS) {
                            $options |= Effect::SET_SATURATION;
                        }

                        if ($setB) {
                            $options |= Effect::SET_BRIGHTNESS;
                        }

                        if ($setK) {
                            $options |= Effect::SET_TEMPERATURE;
                        }

                        $effect = new Effect($transient, $color, $period, $cycles, $skew, $waveform, $options);
                        $effectData = $data . $setHBytes . $setSBytes . $setBBytes . $setKBytes;

                        yield $effectData => $effect;
                    }
                }
            }
        }
    }

    public static function generateTestEffects(bool $withOptions): \Generator
    {
        foreach (self::BOOL_TEST_VALUES as $transientBytes => $transient) {
            foreach (self::generateTestHsbkColors() as $colorBytes => $color) {
                foreach (self::UINT32_TEST_VALUES as $periodBytes => $period) {
                    foreach (self::FLOAT32_TEST_VALUES as $cyclesBytes => $cycles) {
                        foreach (self::INT16_TEST_VALUES as $skewBytes => $skew) {
                            foreach (self::UINT8_TEST_VALUES as $waveformBytes => $waveform) {
                                $data = "\x00" . $transientBytes . $colorBytes . $periodBytes . $cyclesBytes . $skewBytes . $waveformBytes;

                                if ($withOptions) {
                                    yield from self::generateTestEffectsWithOptions($data, $transient, $color, $period, $cycles, $skew, $waveform);
                                } else {
                                    yield $data => new Effect($transient, $color, $period, $cycles, $skew, $waveform);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function reservedBytes(int $count): string
    {
        return \str_repeat("\x00", $count);
    }

}

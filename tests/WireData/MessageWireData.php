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

    public const UINT8_VALUE = 0x2a;
    public const UINT8_BYTES = "\x2a";

    public const INT16_VALUE = -0x8000;
    public const INT16_BYTES = "\x00\x80";

    public const UINT16_VALUE = 0x8000;
    public const UINT16_BYTES = "\x00\x80";

    public const UINT32_VALUE = 0x12345678;
    public const UINT32_BYTES = "\x78\x56\x34\x12";

    public const UINT64_VALUE = 0x123456789abcdef0;
    public const UINT64_BYTES = "\xf0\xde\xbc\x9a\x78\x56\x34\x12";

    public const FLOAT32_VALUE = 3.1415926535897968907;
    public const FLOAT32_BYTES = "\xdb\x0f\x49\x40";

    public const COLOR_TEMPERATURE_VALUE = 4500;
    public const COLOR_TEMPERATURE_BYTES = "\x94\x11";

    private const BOOL_TEST_VALUES = [
        "\x00" => false,
        "\x01" => true,
    ];

    public static function generateTestHsbkColor(): array
    {
        return [
            self::UINT16_BYTES . self::UINT16_BYTES . self::UINT16_BYTES . "\x94\x11",
            new HsbkColor(self::UINT16_VALUE, self::UINT16_VALUE, self::UINT16_VALUE, 4500),
        ];
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
        [$colorBytes, $color] = self::generateTestHsbkColor();

        foreach (self::BOOL_TEST_VALUES as $transientBytes => $transient) {
            $data = "\x00" . $transientBytes . $colorBytes . self::UINT32_BYTES . self::FLOAT32_BYTES . self::INT16_BYTES . self::UINT8_BYTES;

            if ($withOptions) {
                yield from self::generateTestEffectsWithOptions($data, $transient, $color, self::UINT32_VALUE, self::FLOAT32_VALUE, self::INT16_VALUE, self::UINT8_VALUE);
            } else {
                yield $data => new Effect($transient, $color, self::UINT32_VALUE, self::FLOAT32_VALUE, self::INT16_VALUE, self::UINT8_VALUE);
            }
        }
    }

    public static function reservedBytes(int $count): string
    {
        return \str_repeat("\x00", $count);
    }

}

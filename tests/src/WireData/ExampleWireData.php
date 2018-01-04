<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\WireData;

// The data represented here is as defined by the example provided in the official LIFX documentation
// https://lan.developer.lifx.com/docs/building-a-lifx-packet
final class ExampleWireData
{
    public const FRAME_SIZE = 49;
    public const FRAME_ORIGIN = 0;
    public const FRAME_TAGGED_FLAG = true;
    public const FRAME_ADDRESSABLE_FLAG = true;
    public const FRAME_PROTOCOL_NUMBER = 1024;
    public const FRAME_SOURCE = 0;
    public const FRAME_DATA = "\x31\x00\x00\x34\x00\x00\x00\x00";

    public const FRAME_ADDRESS_TARGET_OCTETS = [0, 0, 0, 0, 0, 0];
    public const FRAME_ADDRESS_ACK_FLAG = false;
    public const FRAME_ADDRESS_RES_FLAG = false;
    public const FRAME_ADDRESS_SEQUENCE_NO = 0;
    public const FRAME_ADDRESS_DATA = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";

    public const PROTOCOL_HEADER_MESSAGE_TYPE_ID = 102;
    public const PROTOCOL_HEADER_DATA = "\x00\x00\x00\x00\x00\x00\x00\x00\x66\x00\x00\x00";

    public const HEADER_DATA = self::FRAME_DATA . self::FRAME_ADDRESS_DATA . self::PROTOCOL_HEADER_DATA;

    public const PAYLOAD_HSBK_HUE = 21845;
    public const PAYLOAD_HSBK_SATURATION = 65535;
    public const PAYLOAD_HSBK_BRIGHTNESS = 65535;
    public const PAYLOAD_HSBK_TEMPERATURE = 3500;
    public const PAYLOAD_TRANSITION_TIME = 1024;
    public const PAYLOAD_DATA = "\x00\x55\x55\xFF\xFF\xFF\xFF\xAC\x0D\x00\x04\x00\x00";

    public const PACKET_DATA = self::HEADER_DATA . self::PAYLOAD_DATA;
}

<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LifxLan\Decoding\Exceptions\InvalidMessagePayloadException;
use DaveRandom\LifxLan\Decoding\Exceptions\UnknownMessageTypeException;
use DaveRandom\LifxLan\Messages\Device\Responses\Acknowledgement;
use DaveRandom\LifxLan\Messages\Device\Responses\EchoResponse;
use DaveRandom\LifxLan\Messages\Device\Responses\StateGroup;
use DaveRandom\LifxLan\Messages\Device\Responses\StateHostFirmware;
use DaveRandom\LifxLan\Messages\Device\Responses\StateHostInfo;
use DaveRandom\LifxLan\Messages\Device\Responses\StateLabel;
use DaveRandom\LifxLan\Messages\Device\Responses\StateLocation;
use DaveRandom\LifxLan\Messages\Device\Responses\StatePower as StateDevicePower;
use DaveRandom\LifxLan\Messages\Device\Responses\StateService;
use DaveRandom\LifxLan\Messages\Device\Responses\StateVersion;
use DaveRandom\LifxLan\Messages\Device\Responses\StateWifiFirmware;
use DaveRandom\LifxLan\Messages\Device\Responses\StateWifiInfo;
use DaveRandom\LifxLan\Messages\Light\Responses\State;
use DaveRandom\LifxLan\Messages\Light\Responses\StateInfrared;
use DaveRandom\LifxLan\Messages\Light\Responses\StatePower as StateLightPower;
use DaveRandom\LifxLan\Messages\Message;

final class MessageDecoder
{
    /**
     * @var callable[]
     * @uses decodeAcknowledgement
     * @uses decodeEchoResponse
     * @uses decodeStateGroup
     * @uses decodeStateHostFirmware
     * @uses decodeStateHostInfo
     * @uses decodeStateLabel
     * @uses decodeStateLocation
     * @uses decodeStateDevicePower
     * @uses decodeStateService
     * @uses decodeStateVersion
     * @uses decodeStateWifiFirmware
     * @uses decodeStateWifiInfo
     */
    private const DECODER_ROUTINES = [
        // Device messages
        Acknowledgement::MESSAGE_TYPE_ID => ['self', 'decodeAcknowledgement'],
        EchoResponse::MESSAGE_TYPE_ID => ['self', 'decodeEchoResponse'],
        StateGroup::MESSAGE_TYPE_ID => ['self', 'decodeStateGroup'],
        StateHostFirmware::MESSAGE_TYPE_ID => ['self', 'decodeStateHostFirmware'],
        StateHostInfo::MESSAGE_TYPE_ID => ['self', 'decodeStateHostInfo'],
        StateLabel::MESSAGE_TYPE_ID => ['self', 'decodeStateLabel'],
        StateLocation::MESSAGE_TYPE_ID => ['self', 'decodeStateLocation'],
        StateDevicePower::MESSAGE_TYPE_ID => ['self', 'decodeStateDevicePower'],
        StateService::MESSAGE_TYPE_ID => ['self', 'decodeStateService'],
        StateVersion::MESSAGE_TYPE_ID => ['self', 'decodeStateVersion'],
        StateWifiFirmware::MESSAGE_TYPE_ID => ['self', 'decodeStateWifiFirmware'],
        StateWifiInfo::MESSAGE_TYPE_ID => ['self', 'decodeStateWifiInfo'],

        // Light messages
        State::MESSAGE_TYPE_ID => [], // todo
        StateInfrared::MESSAGE_TYPE_ID => [], // todo
        StateLightPower::MESSAGE_TYPE_ID => [], // todo
    ];

    private static function decodeAcknowledgement(): Acknowledgement
    {
        return new Acknowledgement();
    }

    private static function decodeEchoResponse(string $data): EchoResponse
    {
        return new EchoResponse($data);
    }

    private static function decodeStateGroup(string $data): StateGroup
    {
        // todo
    }

    private static function decodeStateHostFirmware(string $data): StateHostFirmware
    {
        // todo
    }

    private static function decodeStateHostInfo(string $data): StateHostInfo
    {
        // todo
    }

    private static function decodeStateLabel(string $data): StateLabel
    {
        // todo
    }

    private static function decodeStateLocation(string $data): StateLocation
    {
        // todo
    }

    private static function decodeStateDevicePower(string $data): StateDevicePower
    {
        // todo
    }

    /**
     * @param string $data
     * @return StateService
     * @throws InvalidMessagePayloadException
     */
    private static function decodeStateService(string $data): StateService
    {
        if (\strlen($data) !== 5) {
            throw new InvalidMessagePayloadException("Invalid payload length for StateService message, expecting 5 bytes, got " . \strlen($data));
        }

        ['service' => $service, 'port' => $port] = \unpack('Cservice/Vport', $data);

        return new StateService($service, $port);
    }

    private static function decodeStateVersion(string $data): StateVersion
    {
        // todo
    }

    private static function decodeStateWifiFirmware(string $data): StateWifiFirmware
    {
        // todo
    }

    private static function decodeStateWifiInfo(string $data): StateWifiInfo
    {
        // todo
    }

    /**
     * @param int $type
     * @param string $data
     * @return Message
     * @throws DecodingException
     */
    public function decodeMessage(int $type, string $data): Message
    {
        if (!\array_key_exists($type, self::DECODER_ROUTINES)) {
            throw new UnknownMessageTypeException("Unknown message type: {$type}");
        }

        return (self::DECODER_ROUTINES[$type])($data);
    }
}

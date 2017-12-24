<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\DataTypes\HostInfo;
use DaveRandom\LifxLan\DataTypes\Service;
use DaveRandom\LifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LifxLan\Decoding\Exceptions\InvalidMessagePayloadException;
use DaveRandom\LifxLan\Decoding\Exceptions\UnknownMessageTypeException;
use DaveRandom\LifxLan\Messages\Device\Instructions as DeviceInstructions;
use DaveRandom\LifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LifxLan\Messages\Light\Instructions as LightInstructions;
use DaveRandom\LifxLan\Messages\Light\Requests as LightRequests;
use DaveRandom\LifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LifxLan\Messages\Message;

final class MessageDecoder
{
    /**
     * @var callable[]
     * @uses decodeAcknowledgement
     * @uses decodeEchoRequest
     * @uses decodeEchoResponse
     * @uses decodeGetGroup
     * @uses decodeSetGroup
     * @uses decodeStateGroup
     * @uses decodeGetHostFirmware
     * @uses decodeStateHostFirmware
     * @uses decodeGetHostInfo
     * @uses decodeStateHostInfo
     * @uses decodeGetInfo
     * @uses decodeStateInfo
     * @uses decodeGetLabel
     * @uses decodeSetLabel
     * @uses decodeStateLabel
     * @uses decodeGetLocation
     * @uses decodeSetLocation
     * @uses decodeStateLocation
     * @uses decodeGetDevicePower
     * @uses decodeSetDevicePower
     * @uses decodeStateDevicePower
     * @uses decodeGetService
     * @uses decodeStateService
     * @uses decodeGetVersion
     * @uses decodeStateVersion
     * @uses decodeGetWifiFirmware
     * @uses decodeStateWifiFirmware
     * @uses decodeGetWifiInfo
     * @uses decodeStateWifiInfo
     * @uses decodeGet
     * @uses decodeSetColor
     * @uses decodeSetWaveform
     * @uses decodeSetWaveformOptional
     * @uses decodeState
     * @uses decodeGetInfrared
     * @uses decodeSetInfrared
     * @uses decodeStateInfrared
     * @uses decodeGetLightPower
     * @uses decodeSetLightPower
     * @uses decodeStateLightPower
     */
    private const DECODER_ROUTINES = [
        DeviceInstructions\SetGroup::MESSAGE_TYPE_ID => [self::class, 'decodeSetGroup'],
        DeviceInstructions\SetLabel::MESSAGE_TYPE_ID => [self::class, 'decodeSetLabel'],
        DeviceInstructions\SetLocation::MESSAGE_TYPE_ID => [self::class, 'decodeSetLocation'],
        DeviceInstructions\SetPower::MESSAGE_TYPE_ID => [self::class, 'decodeSetDevicePower'],

        DeviceRequests\EchoRequest::MESSAGE_TYPE_ID => [self::class, 'decodeEchoRequest'],
        DeviceRequests\GetGroup::MESSAGE_TYPE_ID => [self::class, 'decodeGetGroup'],
        DeviceRequests\GetHostFirmware::MESSAGE_TYPE_ID => [self::class, 'decodeGetHostFirmware'],
        DeviceRequests\GetHostInfo::MESSAGE_TYPE_ID => [self::class, 'decodeGetHostInfo'],
        DeviceRequests\GetInfo::MESSAGE_TYPE_ID => [self::class, 'decodeGetInfo'],
        DeviceRequests\GetLabel::MESSAGE_TYPE_ID => [self::class, 'decodeGetLabel'],
        DeviceRequests\GetLocation::MESSAGE_TYPE_ID => [self::class, 'decodeGetLocation'],
        DeviceRequests\GetPower::MESSAGE_TYPE_ID => [self::class, 'decodeGetDevicePower'],
        DeviceRequests\GetService::MESSAGE_TYPE_ID => [self::class, 'decodeGetService'],
        DeviceRequests\GetVersion::MESSAGE_TYPE_ID => [self::class, 'decodeGetVersion'],
        DeviceRequests\GetWifiFirmware::MESSAGE_TYPE_ID => [self::class, 'decodeGetWifiFirmware'],
        DeviceRequests\GetWifiInfo::MESSAGE_TYPE_ID => [self::class, 'decodeGetWifiInfo'],

        // Device response messages
        DeviceResponses\Acknowledgement::MESSAGE_TYPE_ID => [self::class, 'decodeAcknowledgement'],
        DeviceResponses\EchoResponse::MESSAGE_TYPE_ID => [self::class, 'decodeEchoResponse'],
        DeviceResponses\StateGroup::MESSAGE_TYPE_ID => [self::class, 'decodeStateGroup'],
        DeviceResponses\StateHostFirmware::MESSAGE_TYPE_ID => [self::class, 'decodeStateHostFirmware'],
        DeviceResponses\StateHostInfo::MESSAGE_TYPE_ID => [self::class, 'decodeStateHostInfo'],
        DeviceResponses\StateInfo::MESSAGE_TYPE_ID => [self::class, 'decodeStateInfo'],
        DeviceResponses\StateLabel::MESSAGE_TYPE_ID => [self::class, 'decodeStateLabel'],
        DeviceResponses\StateLocation::MESSAGE_TYPE_ID => [self::class, 'decodeStateLocation'],
        DeviceResponses\StatePower::MESSAGE_TYPE_ID => [self::class, 'decodeStateDevicePower'],
        DeviceResponses\StateService::MESSAGE_TYPE_ID => [self::class, 'decodeStateService'],
        DeviceResponses\StateVersion::MESSAGE_TYPE_ID => [self::class, 'decodeStateVersion'],
        DeviceResponses\StateWifiFirmware::MESSAGE_TYPE_ID => [self::class, 'decodeStateWifiFirmware'],
        DeviceResponses\StateWifiInfo::MESSAGE_TYPE_ID => [self::class, 'decodeStateWifiInfo'],

        // Light instruction messages
        LightInstructions\SetColor::MESSAGE_TYPE_ID => [self::class, 'decodeSetColor'],
        LightInstructions\SetInfrared::MESSAGE_TYPE_ID => [self::class, 'decodeSetInfrared'],
        LightInstructions\SetPower::MESSAGE_TYPE_ID => [self::class, 'decodeSetLightPower'],
        LightInstructions\SetWaveform::MESSAGE_TYPE_ID => [self::class, 'decodeSetWaveform'],
        LightInstructions\SetWaveformOptional::MESSAGE_TYPE_ID => [self::class, 'decodeSetWaveformOptional'],

        // Light response messages
        LightRequests\Get::MESSAGE_TYPE_ID => [self::class, 'decodeGet'],
        LightRequests\GetInfrared::MESSAGE_TYPE_ID => [self::class, 'decodeGetInfrared'],
        LightRequests\GetPower::MESSAGE_TYPE_ID => [self::class, 'decodeGetLightPower'],

        // Light response messages
        LightResponses\State::MESSAGE_TYPE_ID => [self::class, 'decodeState'],
        LightResponses\StateInfrared::MESSAGE_TYPE_ID => [self::class, 'decodeStateInfrared'],
        LightResponses\StatePower::MESSAGE_TYPE_ID => [self::class, 'decodeStateLightPower'],
    ];

    private static function decodeAcknowledgement(): DeviceResponses\Acknowledgement
    {
        return new DeviceResponses\Acknowledgement();
    }

    private static function decodeEchoRequest(string $data): DeviceRequests\EchoRequest
    {
        return new DeviceRequests\EchoRequest($data);
    }

    private static function decodeEchoResponse(string $data): DeviceResponses\EchoResponse
    {
        return new DeviceResponses\EchoResponse($data);
    }

    private static function decodeGetGroup(string $data): DeviceRequests\GetGroup
    {
        return new DeviceRequests\GetGroup;
    }

    private static function decodeSetGroup(string $data): DeviceInstructions\SetGroup
    {
        // todo
    }

    private static function decodeStateGroup(string $data): DeviceResponses\StateGroup
    {
        // todo
    }

    private static function decodeGetHostFirmware(string $data): DeviceRequests\GetHostFirmware
    {
        return new DeviceRequests\GetHostFirmware;
    }

    private static function decodeStateHostFirmware(string $data): DeviceResponses\StateHostFirmware
    {
        // todo
    }

    private static function decodeGetHostInfo(string $data): DeviceRequests\GetHostInfo
    {
        return new DeviceRequests\GetHostInfo;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateHostInfo
     * @throws InvalidMessagePayloadException
     */
    private static function decodeStateHostInfo(string $data): DeviceResponses\StateHostInfo
    {
        static $format;

        if (\strlen($data) !== 14) {
            throw new InvalidMessagePayloadException(
                "Invalid payload length for StateService message, expecting 14 bytes, got " . \strlen($data)
            );
        }

        if (!isset($format)) {
            $format = \sprintf('%ssignal/Vtx/Vrx');
        }

        ['signal' => $signal, 'tx' => $tx, 'rx' => $rx] = \unpack($format, $data);

        return new DeviceResponses\StateHostInfo(new HostInfo($signal, $tx, $rx));
    }

    private static function decodeGetInfo(string $data): DeviceRequests\GetInfo
    {
        return new DeviceRequests\GetInfo;
    }

    private static function decodeStateInfo(string $data): DeviceResponses\StateInfo
    {
        // todo
    }

    private static function decodeGetLabel(string $data): DeviceRequests\GetLabel
    {
        return new DeviceRequests\GetLabel;
    }

    private static function decodeSetLabel(string $data): DeviceInstructions\SetLabel
    {
        // todo
    }

    private static function decodeStateLabel(string $data): DeviceResponses\StateLabel
    {
        // todo
    }

    private static function decodeGetLocation(string $data): DeviceRequests\GetLocation
    {
        return new DeviceRequests\GetLocation;
    }

    private static function decodeSetLocation(string $data): DeviceInstructions\SetLocation
    {
        // todo
    }

    private static function decodeStateLocation(string $data): DeviceResponses\StateLocation
    {
        // todo
    }

    private static function decodeGetDevicePower(string $data): DeviceRequests\GetPower
    {
        return new DeviceRequests\GetPower;
    }

    private static function decodeSetDevicePower(string $data): DeviceInstructions\SetPower
    {
        // todo
    }

    private static function decodeStateDevicePower(string $data): DeviceResponses\StatePower
    {
        // todo
    }

    private static function decodeGetService(string $data): DeviceRequests\GetService
    {
        return new DeviceRequests\GetService;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateService
     * @throws InvalidMessagePayloadException
     */
    private static function decodeStateService(string $data): DeviceResponses\StateService
    {
        if (\strlen($data) !== 5) {
            throw new InvalidMessagePayloadException(
                "Invalid payload length for StateService message, expecting 5 bytes, got " . \strlen($data)
            );
        }

        ['serviceType' => $serviceType, 'port' => $port] = \unpack('CserviceType/Vport', $data);

        return new DeviceResponses\StateService(new Service($serviceType, $port));
    }

    private static function decodeGetVersion(string $data): DeviceRequests\GetVersion
    {
        return new DeviceRequests\GetVersion;
    }

    private static function decodeStateVersion(string $data): DeviceResponses\StateVersion
    {
        // todo
    }

    private static function decodeGetWifiFirmware(string $data): DeviceRequests\GetWifiFirmware
    {
        return new DeviceRequests\GetWifiFirmware;
    }

    private static function decodeStateWifiFirmware(string $data): DeviceResponses\StateWifiFirmware
    {
        // todo
    }

    private static function decodeGetWifiInfo(string $data): DeviceRequests\GetWifiInfo
    {
        return new DeviceRequests\GetWifiInfo;
    }

    private static function decodeStateWifiInfo(string $data): DeviceResponses\StateWifiInfo
    {
        // todo
    }

    private static function decodeGet(string $data): LightRequests\Get
    {
        return new LightRequests\Get;
    }

    private static function decodeSetColor(string $data): LightInstructions\SetColor
    {
        // todo
    }

    private static function decodeSetWaveform(string $data): LightInstructions\SetWaveform
    {
        // todo
    }

    private static function decodeSetWaveformOptional(string $data): LightInstructions\SetWaveformOptional
    {
        // todo
    }

    private static function decodeState(string $data): LightResponses\State
    {
        // todo
    }

    private static function decodeGetInfrared(string $data): LightRequests\GetInfrared
    {
        return new LightRequests\GetInfrared;
    }

    private static function decodeSetInfrared(string $data): LightInstructions\SetInfrared
    {
        // todo
    }

    private static function decodeStateInfrared(string $data): LightResponses\StateInfrared
    {
        // todo
    }

    private static function decodeGetLightPower(string $data): LightRequests\GetPower
    {
        return new LightRequests\GetPower;
    }

    private static function decodeSetLightPower(string $data): LightInstructions\SetPower
    {
        // todo
    }

    private static function decodeStateLightPower(string $data): LightResponses\StateLightPower
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

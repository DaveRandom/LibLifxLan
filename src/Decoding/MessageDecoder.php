<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\DataTypes\Group;
use DaveRandom\LifxLan\DataTypes\HostFirmware;
use DaveRandom\LifxLan\DataTypes\HostInfo;
use DaveRandom\LifxLan\DataTypes\Info;
use DaveRandom\LifxLan\DataTypes\Location;
use DaveRandom\LifxLan\DataTypes\Service;
use DaveRandom\LifxLan\DataTypes\Version;
use DaveRandom\LifxLan\DataTypes\WifiFirmware;
use DaveRandom\LifxLan\DataTypes\WifiInfo;
use DaveRandom\LifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException;
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
        DeviceInstructions\SetGroup::MESSAGE_TYPE_ID => [0, 'SetGroup'],
        DeviceInstructions\SetLabel::MESSAGE_TYPE_ID => [32, 'SetLabel'],
        DeviceInstructions\SetLocation::MESSAGE_TYPE_ID => [0, 'SetLocation'],
        DeviceInstructions\SetPower::MESSAGE_TYPE_ID => [2, 'SetDevicePower'],

        DeviceRequests\EchoRequest::MESSAGE_TYPE_ID => [64, 'EchoRequest'],
        DeviceRequests\GetGroup::MESSAGE_TYPE_ID => [0, 'GetGroup'],
        DeviceRequests\GetHostFirmware::MESSAGE_TYPE_ID => [0, 'GetHostFirmware'],
        DeviceRequests\GetHostInfo::MESSAGE_TYPE_ID => [0, 'GetHostInfo'],
        DeviceRequests\GetInfo::MESSAGE_TYPE_ID => [0, 'GetInfo'],
        DeviceRequests\GetLabel::MESSAGE_TYPE_ID => [0, 'GetLabel'],
        DeviceRequests\GetLocation::MESSAGE_TYPE_ID => [0, 'GetLocation'],
        DeviceRequests\GetPower::MESSAGE_TYPE_ID => [0, 'GetDevicePower'],
        DeviceRequests\GetService::MESSAGE_TYPE_ID => [0, 'GetService'],
        DeviceRequests\GetVersion::MESSAGE_TYPE_ID => [0, 'GetVersion'],
        DeviceRequests\GetWifiFirmware::MESSAGE_TYPE_ID => [0, 'GetWifiFirmware'],
        DeviceRequests\GetWifiInfo::MESSAGE_TYPE_ID => [0, 'GetWifiInfo'],

        // Device response messages
        DeviceResponses\Acknowledgement::MESSAGE_TYPE_ID => [0, 'Acknowledgement'],
        DeviceResponses\EchoResponse::MESSAGE_TYPE_ID => [64, 'EchoResponse'],
        DeviceResponses\StateGroup::MESSAGE_TYPE_ID => [0, 'StateGroup'],
        DeviceResponses\StateHostFirmware::MESSAGE_TYPE_ID => [20, 'StateHostFirmware'],
        DeviceResponses\StateHostInfo::MESSAGE_TYPE_ID => [14, 'StateHostInfo'],
        DeviceResponses\StateInfo::MESSAGE_TYPE_ID => [24, 'StateInfo'],
        DeviceResponses\StateLabel::MESSAGE_TYPE_ID => [32, 'StateLabel'],
        DeviceResponses\StateLocation::MESSAGE_TYPE_ID => [0, 'StateLocation'],
        DeviceResponses\StatePower::MESSAGE_TYPE_ID => [2, 'StateDevicePower'],
        DeviceResponses\StateService::MESSAGE_TYPE_ID => [5, 'StateService'],
        DeviceResponses\StateVersion::MESSAGE_TYPE_ID => [12, 'StateVersion'],
        DeviceResponses\StateWifiFirmware::MESSAGE_TYPE_ID => [20, 'StateWifiFirmware'],
        DeviceResponses\StateWifiInfo::MESSAGE_TYPE_ID => [14, 'StateWifiInfo'],

        // Light instruction messages
        LightInstructions\SetColor::MESSAGE_TYPE_ID => [0, 'SetColor'],
        LightInstructions\SetInfrared::MESSAGE_TYPE_ID => [0, 'SetInfrared'],
        LightInstructions\SetPower::MESSAGE_TYPE_ID => [0, 'SetLightPower'],
        LightInstructions\SetWaveform::MESSAGE_TYPE_ID => [0, 'SetWaveform'],
        LightInstructions\SetWaveformOptional::MESSAGE_TYPE_ID => [0, 'SetWaveformOptional'],

        // Light response messages
        LightRequests\Get::MESSAGE_TYPE_ID => [0, 'Get'],
        LightRequests\GetInfrared::MESSAGE_TYPE_ID => [0, 'GetInfrared'],
        LightRequests\GetPower::MESSAGE_TYPE_ID => [0, 'GetLightPower'],

        // Light response messages
        LightResponses\State::MESSAGE_TYPE_ID => [0, 'State'],
        LightResponses\StateInfrared::MESSAGE_TYPE_ID => [0, 'StateInfrared'],
        LightResponses\StatePower::MESSAGE_TYPE_ID => [0, 'StateLightPower'],
    ];

    private static function unsignedShortToSignedShort(int $unsigned): int
    {
        if (!($unsigned & 0x8000)) {
            return $unsigned;
        }

        return -(($unsigned & 0x7fff) + 1);
    }

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

    private static function decodeGetGroup(): DeviceRequests\GetGroup
    {
        return new DeviceRequests\GetGroup;
    }

    private static function decodeSetGroup(string $data): DeviceInstructions\SetGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('aguid/alabel/Pupdated', $data);

        return new DeviceInstructions\SetGroup(new Group($guid, $label, $updatedAt));
    }

    private static function decodeStateGroup(string $data): DeviceResponses\StateGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('aguid/alabel/Pupdated', $data);

        return new DeviceResponses\StateGroup(new Group($guid, $label, $updatedAt));
    }

    private static function decodeGetHostFirmware(): DeviceRequests\GetHostFirmware
    {
        return new DeviceRequests\GetHostFirmware;
    }

    private static function decodeStateHostFirmware(string $data): DeviceResponses\StateHostFirmware
    {
        [
            'build'    => $build,
            'reserved' => $reserved,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data);

        return new DeviceResponses\StateHostFirmware(new HostFirmware($build, $reserved, $version));
    }

    private static function decodeGetHostInfo(): DeviceRequests\GetHostInfo
    {
        return new DeviceRequests\GetHostInfo;
    }

    private static function decodeStateHostInfo(string $data): DeviceResponses\StateHostInfo
    {
        static $format;

        if (!isset($format)) {
            $format = \sprintf('%ssignal/Vtx/Vrx/vreserved');
        }

        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
            'reserved' => $reserved,
        ] = \unpack($format, $data);

        $reserved = self::unsignedShortToSignedShort($reserved);

        return new DeviceResponses\StateHostInfo(new HostInfo($signal, $tx, $rx, $reserved));
    }

    private static function decodeGetInfo(): DeviceRequests\GetInfo
    {
        return new DeviceRequests\GetInfo;
    }

    private static function decodeStateInfo(string $data): DeviceResponses\StateInfo
    {
        [
            'time'     => $time,
            'uptime'   => $uptime,
            'downtime' => $downtime,
        ] = \unpack('Ptime/Puptime/Pdowntime', $data);

        return new DeviceResponses\StateInfo(new Info($time, $uptime, $downtime));
    }

    private static function decodeGetLabel(): DeviceRequests\GetLabel
    {
        return new DeviceRequests\GetLabel;
    }

    private static function decodeSetLabel(string $data): DeviceInstructions\SetLabel
    {
        return new DeviceInstructions\SetLabel($data);
    }

    private static function decodeStateLabel(string $data): DeviceResponses\StateLabel
    {
        return new DeviceResponses\StateLabel($data);
    }

    private static function decodeGetLocation(): DeviceRequests\GetLocation
    {
        return new DeviceRequests\GetLocation;
    }

    private static function decodeSetLocation(string $data): DeviceInstructions\SetLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('aguid/alabel/Pupdated', $data);

        return new DeviceInstructions\SetLocation(new Location($guid, $label, $updatedAt));
    }

    private static function decodeStateLocation(string $data): DeviceResponses\StateLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('aguid/alabel/Pupdated', $data);

        return new DeviceResponses\StateLocation(new Location($guid, $label, $updatedAt));
    }

    private static function decodeGetDevicePower(): DeviceRequests\GetPower
    {
        return new DeviceRequests\GetPower;
    }

    private static function decodeSetDevicePower(string $data): DeviceInstructions\SetPower
    {
        $level = \unpack('vlevel', $data)['level'];

        return new DeviceInstructions\SetPower($level);
    }

    private static function decodeStateDevicePower(string $data): DeviceResponses\StatePower
    {
        $level = \unpack('vlevel', $data)['level'];

        return new DeviceResponses\StatePower($level);
    }

    private static function decodeGetService(): DeviceRequests\GetService
    {
        return new DeviceRequests\GetService;
    }

    private static function decodeStateService(string $data): DeviceResponses\StateService
    {
        [
            'serviceType' => $serviceType,
            'port' => $port,
        ] = \unpack('CserviceType/Vport', $data);

        return new DeviceResponses\StateService(new Service($serviceType, $port));
    }

    private static function decodeGetVersion(): DeviceRequests\GetVersion
    {
        return new DeviceRequests\GetVersion;
    }

    private static function decodeStateVersion(string $data): DeviceResponses\StateVersion
    {
        [
            'vendor'  => $vendor,
            'product' => $product,
            'version' => $version,
        ] = \unpack('Vvendor/Vproduct/Vversion', $data);

        return new DeviceResponses\StateVersion(new Version($vendor, $product, $version));
    }

    private static function decodeGetWifiFirmware(): DeviceRequests\GetWifiFirmware
    {
        return new DeviceRequests\GetWifiFirmware;
    }

    private static function decodeStateWifiFirmware(string $data): DeviceResponses\StateWifiFirmware
    {
        [
            'build'    => $build,
            'reserved' => $reserved,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data);

        return new DeviceResponses\StateWifiFirmware(new WifiFirmware($build, $reserved, $version));
    }

    private static function decodeGetWifiInfo(): DeviceRequests\GetWifiInfo
    {
        return new DeviceRequests\GetWifiInfo;
    }

    private static function decodeStateWifiInfo(string $data): DeviceResponses\StateWifiInfo
    {
        static $format;

        if (!isset($format)) {
            $format = \sprintf('%ssignal/Vtx/Vrx/vreserved');
        }

        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
            'reserved' => $reserved,
        ] = \unpack($format, $data);

        $reserved = self::unsignedShortToSignedShort($reserved);

        return new DeviceResponses\StateWifiInfo(new WifiInfo($signal, $tx, $rx, $reserved));
    }

    private static function decodeGet(): LightRequests\Get
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

    private static function decodeGetInfrared(): LightRequests\GetInfrared
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

    private static function decodeGetLightPower(): LightRequests\GetPower
    {
        return new LightRequests\GetPower;
    }

    private static function decodeSetLightPower(string $data): LightInstructions\SetPower
    {
        // todo
    }

    private static function decodeStateLightPower(string $data): LightResponses\StatePower
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

        [$payloadLength, $messageName] = self::DECODER_ROUTINES[$type];

        if (isset($payloadLength) && \strlen($data) !== $payloadLength) {
            throw new InvalidMessagePayloadLengthException(
                "Invalid payload length for {$messageName} message, expecting {$payloadLength} bytes, got " . \strlen($data)
            );
        }

        return ([self::class, $messageName])($data);
    }
}

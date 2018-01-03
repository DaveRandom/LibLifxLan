<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\DataTypes as DeviceDataTypes;
use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\MalformedMessagePayloadException;
use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use DaveRandom\LibLifxLan\Messages\Device\Commands as DeviceCommands;
use DaveRandom\LibLifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LibLifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LibLifxLan\Messages\Light\Commands as LightCommmands;
use DaveRandom\LibLifxLan\Messages\Light\Requests as LightRequests;
use DaveRandom\LibLifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\LibLifxLan\Messages\UnknownMessage;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

final class MessageDecoder
{
    private const HSBK_FORMAT = 'vhue/vsaturation/vbrightness/vtemperature';

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
    private const MESSAGE_INFO = [
        // Device command messages
        DeviceCommands\SetGroup::MESSAGE_TYPE_ID => [DeviceCommands\SetGroup::WIRE_SIZE, 'SetGroup'],
        DeviceCommands\SetLabel::MESSAGE_TYPE_ID => [DeviceCommands\SetLabel::WIRE_SIZE, 'SetLabel'],
        DeviceCommands\SetLocation::MESSAGE_TYPE_ID => [DeviceCommands\SetLocation::WIRE_SIZE, 'SetLocation'],
        DeviceCommands\SetPower::MESSAGE_TYPE_ID => [DeviceCommands\SetPower::WIRE_SIZE, 'SetDevicePower'],

        // Device request messages
        DeviceRequests\EchoRequest::MESSAGE_TYPE_ID => [DeviceRequests\EchoRequest::WIRE_SIZE, 'EchoRequest'],
        DeviceRequests\GetGroup::MESSAGE_TYPE_ID => [DeviceRequests\GetGroup::WIRE_SIZE, 'GetGroup'],
        DeviceRequests\GetHostFirmware::MESSAGE_TYPE_ID => [DeviceRequests\GetHostFirmware::WIRE_SIZE, 'GetHostFirmware'],
        DeviceRequests\GetHostInfo::MESSAGE_TYPE_ID => [DeviceRequests\GetHostInfo::WIRE_SIZE, 'GetHostInfo'],
        DeviceRequests\GetInfo::MESSAGE_TYPE_ID => [DeviceRequests\GetInfo::WIRE_SIZE, 'GetInfo'],
        DeviceRequests\GetLabel::MESSAGE_TYPE_ID => [DeviceRequests\GetLabel::WIRE_SIZE, 'GetLabel'],
        DeviceRequests\GetLocation::MESSAGE_TYPE_ID => [DeviceRequests\GetLocation::WIRE_SIZE, 'GetLocation'],
        DeviceRequests\GetPower::MESSAGE_TYPE_ID => [DeviceRequests\GetPower::WIRE_SIZE, 'GetDevicePower'],
        DeviceRequests\GetService::MESSAGE_TYPE_ID => [DeviceRequests\GetService::WIRE_SIZE, 'GetService'],
        DeviceRequests\GetVersion::MESSAGE_TYPE_ID => [DeviceRequests\GetVersion::WIRE_SIZE, 'GetVersion'],
        DeviceRequests\GetWifiFirmware::MESSAGE_TYPE_ID => [DeviceRequests\GetWifiFirmware::WIRE_SIZE, 'GetWifiFirmware'],
        DeviceRequests\GetWifiInfo::MESSAGE_TYPE_ID => [DeviceRequests\GetWifiInfo::WIRE_SIZE, 'GetWifiInfo'],

        // Device response messages
        DeviceResponses\Acknowledgement::MESSAGE_TYPE_ID => [DeviceResponses\Acknowledgement::WIRE_SIZE, 'Acknowledgement'],
        DeviceResponses\EchoResponse::MESSAGE_TYPE_ID => [DeviceResponses\EchoResponse::WIRE_SIZE, 'EchoResponse'],
        DeviceResponses\StateGroup::MESSAGE_TYPE_ID => [DeviceResponses\StateGroup::WIRE_SIZE, 'StateGroup'],
        DeviceResponses\StateHostFirmware::MESSAGE_TYPE_ID => [DeviceResponses\StateHostFirmware::WIRE_SIZE, 'StateHostFirmware'],
        DeviceResponses\StateHostInfo::MESSAGE_TYPE_ID => [DeviceResponses\StateHostInfo::WIRE_SIZE, 'StateHostInfo'],
        DeviceResponses\StateInfo::MESSAGE_TYPE_ID => [DeviceResponses\StateInfo::WIRE_SIZE, 'StateInfo'],
        DeviceResponses\StateLabel::MESSAGE_TYPE_ID => [DeviceResponses\StateLabel::WIRE_SIZE, 'StateLabel'],
        DeviceResponses\StateLocation::MESSAGE_TYPE_ID => [DeviceResponses\StateLocation::WIRE_SIZE, 'StateLocation'],
        DeviceResponses\StatePower::MESSAGE_TYPE_ID => [DeviceResponses\StatePower::WIRE_SIZE, 'StateDevicePower'],
        DeviceResponses\StateService::MESSAGE_TYPE_ID => [DeviceResponses\StateService::WIRE_SIZE, 'StateService'],
        DeviceResponses\StateVersion::MESSAGE_TYPE_ID => [DeviceResponses\StateVersion::WIRE_SIZE, 'StateVersion'],
        DeviceResponses\StateWifiFirmware::MESSAGE_TYPE_ID => [DeviceResponses\StateWifiFirmware::WIRE_SIZE, 'StateWifiFirmware'],
        DeviceResponses\StateWifiInfo::MESSAGE_TYPE_ID => [DeviceResponses\StateWifiInfo::WIRE_SIZE, 'StateWifiInfo'],

        // Light command messages
        LightCommmands\SetColor::MESSAGE_TYPE_ID => [LightCommmands\SetColor::WIRE_SIZE, 'SetColor'],
        LightCommmands\SetInfrared::MESSAGE_TYPE_ID => [LightCommmands\SetInfrared::WIRE_SIZE, 'SetInfrared'],
        LightCommmands\SetPower::MESSAGE_TYPE_ID => [LightCommmands\SetPower::WIRE_SIZE, 'SetLightPower'],
        LightCommmands\SetWaveform::MESSAGE_TYPE_ID => [LightCommmands\SetWaveform::WIRE_SIZE, 'SetWaveform'],
        LightCommmands\SetWaveformOptional::MESSAGE_TYPE_ID => [LightCommmands\SetWaveformOptional::WIRE_SIZE, 'SetWaveformOptional'],

        // Light request messages
        LightRequests\Get::MESSAGE_TYPE_ID => [LightRequests\Get::WIRE_SIZE, 'Get'],
        LightRequests\GetInfrared::MESSAGE_TYPE_ID => [LightRequests\GetInfrared::WIRE_SIZE, 'GetInfrared'],
        LightRequests\GetPower::MESSAGE_TYPE_ID => [LightRequests\GetPower::WIRE_SIZE, 'GetLightPower'],

        // Light response messages
        LightResponses\State::MESSAGE_TYPE_ID => [LightResponses\State::WIRE_SIZE, 'State'],
        LightResponses\StateInfrared::MESSAGE_TYPE_ID => [LightResponses\StateInfrared::WIRE_SIZE, 'StateInfrared'],
        LightResponses\StatePower::MESSAGE_TYPE_ID => [LightResponses\StatePower::WIRE_SIZE, 'StateLightPower'],
    ];

    private $uuidFactory;

    private function unsignedShortToSignedShort(int $unsigned): int
    {
        if (!($unsigned & 0x8000)) {
            return $unsigned;
        }

        return -(($unsigned & 0x7fff) + 1);
    }

    /**
     * @param int $timestamp
     * @return \DateTimeImmutable
     * @throws InvalidValueException
     */
    private function nanotimeToDateTimeImmutable(int $timestamp): \DateTimeImmutable
    {
        static $utcTimeZone;
        
        $usecs = (int)(($timestamp % 1000000000) / 1000);
        $secs = (int)($timestamp / 1000000000);

        $result = \DateTimeImmutable::createFromFormat(
            'u U',
            \sprintf("%06d %d", $usecs, $secs),
            $utcTimeZone ?? ($utcTimeZone = new \DateTimeZone('UTC'))
        );

        if ($result === false) {
            throw new InvalidValueException("Could not convert nanotime to DateTimeImmutable instance");
        }

        return $result;
    }

    private function decodeAcknowledgement(): DeviceResponses\Acknowledgement
    {
        return new DeviceResponses\Acknowledgement();
    }

    private function decodeEchoRequest(string $data): DeviceRequests\EchoRequest
    {
        return new DeviceRequests\EchoRequest($data);
    }

    private function decodeEchoResponse(string $data): DeviceResponses\EchoResponse
    {
        return new DeviceResponses\EchoResponse($data);
    }

    private function decodeGetGroup(): DeviceRequests\GetGroup
    {
        return new DeviceRequests\GetGroup;
    }

    /**
     * @param string $data
     * @return DeviceCommands\SetGroup
     * @throws InvalidValueException
     */
    private function decodeSetGroup(string $data): DeviceCommands\SetGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = $this->nanotimeToDateTimeImmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceCommands\SetGroup(new DeviceDataTypes\Group($guid, $label, $updatedAt));
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateGroup
     * @throws InvalidValueException
     */
    private function decodeStateGroup(string $data): DeviceResponses\StateGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = $this->nanotimeToDateTimeImmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceResponses\StateGroup(new DeviceDataTypes\Group($guid, $label, $updatedAt));
    }

    private function decodeGetHostFirmware(): DeviceRequests\GetHostFirmware
    {
        return new DeviceRequests\GetHostFirmware;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateHostFirmware
     * @throws InvalidValueException
     */
    private function decodeStateHostFirmware(string $data): DeviceResponses\StateHostFirmware
    {
        [
            'build'    => $build,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data);

        $build = $this->nanotimeToDateTimeImmutable($build);

        return new DeviceResponses\StateHostFirmware(new DeviceDataTypes\HostFirmware($build, $version));
    }

    private function decodeGetHostInfo(): DeviceRequests\GetHostInfo
    {
        return new DeviceRequests\GetHostInfo;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateHostInfo
     */
    private function decodeStateHostInfo(string $data): DeviceResponses\StateHostInfo
    {
        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
        ] = \unpack(\DaveRandom\LibLifxLan\FLOAT32_CODE . 'signal/Vtx/Vrx/vreserved', $data);

        return new DeviceResponses\StateHostInfo(new DeviceDataTypes\HostInfo($signal, $tx, $rx));
    }

    private function decodeGetInfo(): DeviceRequests\GetInfo
    {
        return new DeviceRequests\GetInfo;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateInfo
     * @throws InvalidValueException
     */
    private function decodeStateInfo(string $data): DeviceResponses\StateInfo
    {
        [
            'time'     => $time,
            'uptime'   => $uptime,
            'downtime' => $downtime,
        ] = \unpack('Ptime/Puptime/Pdowntime', $data);

        $time = $this->nanotimeToDateTimeImmutable($time);

        return new DeviceResponses\StateInfo(new DeviceDataTypes\TimeInfo($time, $uptime, $downtime));
    }

    private function decodeGetLabel(): DeviceRequests\GetLabel
    {
        return new DeviceRequests\GetLabel;
    }

    private function decodeSetLabel(string $data): DeviceCommands\SetLabel
    {
        return new DeviceCommands\SetLabel(new DeviceDataTypes\Label(\rtrim($data, "\x00")));
    }

    private function decodeStateLabel(string $data): DeviceResponses\StateLabel
    {
        return new DeviceResponses\StateLabel(new DeviceDataTypes\Label(\rtrim($data, "\x00")));
    }

    private function decodeGetLocation(): DeviceRequests\GetLocation
    {
        return new DeviceRequests\GetLocation;
    }

    /**
     * @param string $data
     * @return DeviceCommands\SetLocation
     * @throws InvalidValueException
     */
    private function decodeSetLocation(string $data): DeviceCommands\SetLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = $this->nanotimeToDateTimeImmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceCommands\SetLocation(new DeviceDataTypes\Location($guid, $label, $updatedAt));
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateLocation
     * @throws InvalidValueException
     */
    private function decodeStateLocation(string $data): DeviceResponses\StateLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = $this->nanotimeToDateTimeImmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceResponses\StateLocation(new DeviceDataTypes\Location($guid, $label, $updatedAt));
    }

    private function decodeGetDevicePower(): DeviceRequests\GetPower
    {
        return new DeviceRequests\GetPower;
    }

    private function decodeSetDevicePower(string $data): DeviceCommands\SetPower
    {
        $level = \unpack('vlevel', $data)['level'];

        return new DeviceCommands\SetPower($level);
    }

    private function decodeStateDevicePower(string $data): DeviceResponses\StatePower
    {
        $level = \unpack('vlevel', $data)['level'];

        return new DeviceResponses\StatePower($level);
    }

    private function decodeGetService(): DeviceRequests\GetService
    {
        return new DeviceRequests\GetService;
    }

    private function decodeStateService(string $data): DeviceResponses\StateService
    {
        [
            'serviceType' => $serviceType,
            'port' => $port,
        ] = \unpack('CserviceType/Vport', $data);

        return new DeviceResponses\StateService(new DeviceDataTypes\Service($serviceType, $port));
    }

    private function decodeGetVersion(): DeviceRequests\GetVersion
    {
        return new DeviceRequests\GetVersion;
    }

    private function decodeStateVersion(string $data): DeviceResponses\StateVersion
    {
        [
            'vendor'  => $vendor,
            'product' => $product,
            'version' => $version,
        ] = \unpack('Vvendor/Vproduct/Vversion', $data);

        return new DeviceResponses\StateVersion(new DeviceDataTypes\Version($vendor, $product, $version));
    }

    private function decodeGetWifiFirmware(): DeviceRequests\GetWifiFirmware
    {
        return new DeviceRequests\GetWifiFirmware;
    }

    /**
     * @param string $data
     * @return DeviceResponses\StateWifiFirmware
     * @throws InvalidValueException
     */
    private function decodeStateWifiFirmware(string $data): DeviceResponses\StateWifiFirmware
    {
        [
            'build'    => $build,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data);

        $build = $this->nanotimeToDateTimeImmutable($build);

        return new DeviceResponses\StateWifiFirmware(new DeviceDataTypes\WifiFirmware($build, $version));
    }

    private function decodeGetWifiInfo(): DeviceRequests\GetWifiInfo
    {
        return new DeviceRequests\GetWifiInfo;
    }

    private function decodeStateWifiInfo(string $data): DeviceResponses\StateWifiInfo
    {

        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
        ] = \unpack(\DaveRandom\LibLifxLan\FLOAT32_CODE . 'signal/Vtx/Vrx/vreserved', $data);

        return new DeviceResponses\StateWifiInfo(new DeviceDataTypes\WifiInfo($signal, $tx, $rx));
    }

    private function decodeGet(): LightRequests\Get
    {
        return new LightRequests\Get;
    }

    private function decodeSetColor(string $data): LightCommmands\SetColor
    {
        [
            'hue'         => $hue,
            'saturation'  => $saturation,
            'brightness'  => $brightness,
            'temperature' => $temperature,
            'duration'    => $duration,
        ] = \unpack('Creserved/' . self::HSBK_FORMAT . '/Vduration', $data);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);

        return new LightCommmands\SetColor(new LightDataTypes\ColorTransition($color, $duration));
    }

    private function decodeSetWaveform(string $data): LightCommmands\SetWaveform
    {
        $format
            = 'Creserved/Ctransient/'
            . self::HSBK_FORMAT
            . '/Vperiod/' . \DaveRandom\LibLifxLan\FLOAT32_CODE . 'cycles/vskewRatio/Cwaveform'
        ;

        [
            'transient'   => $transient,
            'hue'         => $hue,
            'saturation'  => $saturation,
            'brightness'  => $brightness,
            'temperature' => $temperature,
            'period'      => $period,
            'cycles'      => $cycles,
            'skewRatio'   => $skewRatio,
            'waveform'    => $waveform,
        ] = \unpack($format, $data);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);

        $skewRatio = $this->unsignedShortToSignedShort($skewRatio);
        $effect = new LightDataTypes\Effect((bool)$transient, $color, $period, $cycles, $skewRatio, $waveform);

        return new LightCommmands\SetWaveform($effect);
    }

    private function decodeSetWaveformOptional(string $data): LightCommmands\SetWaveformOptional
    {
        $format
            = 'Creserved/Ctransient/'
            . self::HSBK_FORMAT
            . '/Vperiod/' . \DaveRandom\LibLifxLan\FLOAT32_CODE . 'cycles/vskewRatio/Cwaveform'
            . '/CsetHue/CsetSaturation/CsetBrightness/CsetTemperature'
        ;

        [
            'transient'      => $transient,
            'hue'            => $hue,
            'saturation'     => $saturation,
            'brightness'     => $brightness,
            'temperature'    => $temperature,
            'period'         => $period,
            'cycles'         => $cycles,
            'skewRatio'      => $skewRatio,
            'waveform'       => $waveform,
            'setHue'         => $setHue,
            'setSaturation'  => $setSaturation,
            'setBrightness'  => $setBrightness,
            'setTemperature' => $setTemperature,
        ] = \unpack($format, $data);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);
        $skewRatio = $this->unsignedShortToSignedShort($skewRatio);

        $options = ($setHue ? LightDataTypes\Effect::SET_HUE : 0)
            | ($setSaturation ? LightDataTypes\Effect::SET_SATURATION : 0)
            | ($setBrightness ? LightDataTypes\Effect::SET_BRIGHTNESS : 0)
            | ($setTemperature ? LightDataTypes\Effect::SET_TEMPERATURE : 0);

        $effect = new LightDataTypes\Effect((bool)$transient, $color, $period, $cycles, $skewRatio, $waveform, $options);

        return new LightCommmands\SetWaveformOptional($effect);
    }

    private function decodeState(string $data): LightResponses\State
    {
        [
            'hue'         => $hue,
            'saturation'  => $saturation,
            'brightness'  => $brightness,
            'temperature' => $temperature,
            'power'       => $power,
            'label'       => $label,
        ] = \unpack(self::HSBK_FORMAT . '/vreserved/vpower/a32label/Preserved', $data);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new LightResponses\State(new LightDataTypes\State($color, $power, $label));
    }

    private function decodeGetInfrared(): LightRequests\GetInfrared
    {
        return new LightRequests\GetInfrared;
    }

    private function decodeSetInfrared(string $data): LightCommmands\SetInfrared
    {
        $level = \unpack('vlevel', $data)['level'];

        return new LightCommmands\SetInfrared($level);
    }

    private function decodeStateInfrared(string $data): LightResponses\StateInfrared
    {
        $level = \unpack('vlevel', $data)['level'];

        return new LightResponses\StateInfrared($level);
    }

    private function decodeGetLightPower(): LightRequests\GetPower
    {
        return new LightRequests\GetPower;
    }

    private function decodeSetLightPower(string $data): LightCommmands\SetPower
    {
        [
            'level'    => $level,
            'duration' => $duration,
        ] = \unpack('vlevel/Vduration', $data);

        return new LightCommmands\SetPower(new LightDataTypes\PowerTransition($level, $duration));
    }

    private function decodeStateLightPower(string $data): LightResponses\StatePower
    {
        $level = \unpack('vlevel', $data)['level'];

        return new LightResponses\StatePower($level);
    }

    public function __construct(UuidFactoryInterface $uuidFactory = null)
    {
        $this->uuidFactory = $uuidFactory ?? new UuidFactory;
    }

    /**
     * @param int $type
     * @param string $data
     * @return Message
     * @throws DecodingException
     */
    public function decodeMessage(int $type, string $data): Message
    {
        if (!\array_key_exists($type, self::MESSAGE_INFO)) {
            return new UnknownMessage($type, $data);
        }

        [$payloadLength, $messageName] = self::MESSAGE_INFO[$type];

        if (\strlen($data) !== $payloadLength) {
            throw new InvalidMessagePayloadLengthException(
                "Invalid payload length for {$messageName} message,"
                . " expecting {$payloadLength} bytes, got " . \strlen($data)
            );
        }

        try {
            return ([$this, 'decode' . $messageName])($data);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (InvalidValueException $e) {
            throw new MalformedMessagePayloadException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

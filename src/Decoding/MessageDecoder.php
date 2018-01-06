<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Decoding;

use DaveRandom\LibLifxLan\DataTypes as DeviceDataTypes;
use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException;
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
use function DaveRandom\LibLifxLan\nanotime_to_datetimeimmutable;
use function DaveRandom\LibLifxLan\uint16_to_int16;

final class MessageDecoder
{
    private const HSBK_FORMAT = 'vhue/vsaturation/vbrightness/vtemperature';

    private const SET_WAVEFORM_FORMAT
        = 'Creserved/Ctransient'
        . '/' . self::HSBK_FORMAT
        . '/Vperiod/' . \DaveRandom\LibLifxLan\FLOAT32_CODE . 'cycles/vskewRatio/Cwaveform'
    ;

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

    private function createBitField(array $map): int
    {
        $result = 0;

        foreach ($map as $flag => $enabled) {
            if ($enabled) {
                $result |= $flag;
            }
        }

        return $result;
    }

    private function decodeAcknowledgement(): DeviceResponses\Acknowledgement
    {
        return new DeviceResponses\Acknowledgement();
    }

    private function decodeEchoRequest(string $data, int $offset): DeviceRequests\EchoRequest
    {
        return new DeviceRequests\EchoRequest(\substr($data, $offset));
    }

    private function decodeEchoResponse(string $data, int $offset): DeviceResponses\EchoResponse
    {
        return new DeviceResponses\EchoResponse(\substr($data, $offset));
    }

    private function decodeGetGroup(): DeviceRequests\GetGroup
    {
        return new DeviceRequests\GetGroup;
    }

    private function decodeSetGroup(string $data, int $offset): DeviceCommands\SetGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data, $offset);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = nanotime_to_datetimeimmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceCommands\SetGroup(new DeviceDataTypes\Group($guid, $label, $updatedAt));
    }

    private function decodeStateGroup(string $data, int $offset): DeviceResponses\StateGroup
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data, $offset);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = nanotime_to_datetimeimmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceResponses\StateGroup(new DeviceDataTypes\Group($guid, $label, $updatedAt));
    }

    private function decodeGetHostFirmware(): DeviceRequests\GetHostFirmware
    {
        return new DeviceRequests\GetHostFirmware;
    }

    private function decodeStateHostFirmware(string $data, int $offset): DeviceResponses\StateHostFirmware
    {
        [
            'build'    => $build,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data, $offset);

        $build = nanotime_to_datetimeimmutable($build);

        return new DeviceResponses\StateHostFirmware(new DeviceDataTypes\HostFirmware($build, $version));
    }

    private function decodeGetHostInfo(): DeviceRequests\GetHostInfo
    {
        return new DeviceRequests\GetHostInfo;
    }

    private function decodeStateHostInfo(string $data, int $offset): DeviceResponses\StateHostInfo
    {
        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
        ] = \unpack(\DaveRandom\LibLifxLan\FLOAT32_CODE . 'signal/Vtx/Vrx/vreserved', $data, $offset);

        return new DeviceResponses\StateHostInfo(new DeviceDataTypes\HostInfo($signal, $tx, $rx));
    }

    private function decodeGetInfo(): DeviceRequests\GetInfo
    {
        return new DeviceRequests\GetInfo;
    }

    private function decodeStateInfo(string $data, int $offset): DeviceResponses\StateInfo
    {
        [
            'time'     => $time,
            'uptime'   => $uptime,
            'downtime' => $downtime,
        ] = \unpack('Ptime/Puptime/Pdowntime', $data, $offset);

        $time = nanotime_to_datetimeimmutable($time);

        return new DeviceResponses\StateInfo(new DeviceDataTypes\TimeInfo($time, $uptime, $downtime));
    }

    private function decodeGetLabel(): DeviceRequests\GetLabel
    {
        return new DeviceRequests\GetLabel;
    }

    private function decodeSetLabel(string $data, int $offset): DeviceCommands\SetLabel
    {
        return new DeviceCommands\SetLabel(new DeviceDataTypes\Label(\rtrim(\substr($data, $offset), "\x00")));
    }

    private function decodeStateLabel(string $data, int $offset): DeviceResponses\StateLabel
    {
        return new DeviceResponses\StateLabel(new DeviceDataTypes\Label(\rtrim(\substr($data, $offset), "\x00")));
    }

    private function decodeGetLocation(): DeviceRequests\GetLocation
    {
        return new DeviceRequests\GetLocation;
    }

    private function decodeSetLocation(string $data, int $offset): DeviceCommands\SetLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data, $offset);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = nanotime_to_datetimeimmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceCommands\SetLocation(new DeviceDataTypes\Location($guid, $label, $updatedAt));
    }

    private function decodeStateLocation(string $data, int $offset): DeviceResponses\StateLocation
    {
        [
            'guid'    => $guid,
            'label'   => $label,
            'updated' => $updatedAt,
        ] = \unpack('a16guid/a32label/Pupdated', $data, $offset);

        $guid = $this->uuidFactory->fromBytes($guid);
        $updatedAt = nanotime_to_datetimeimmutable($updatedAt);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new DeviceResponses\StateLocation(new DeviceDataTypes\Location($guid, $label, $updatedAt));
    }

    private function decodeGetDevicePower(): DeviceRequests\GetPower
    {
        return new DeviceRequests\GetPower;
    }

    private function decodeSetDevicePower(string $data, int $offset): DeviceCommands\SetPower
    {
        $level = \unpack('vlevel', $data, $offset)['level'];

        return new DeviceCommands\SetPower($level);
    }

    private function decodeStateDevicePower(string $data, int $offset): DeviceResponses\StatePower
    {
        $level = \unpack('vlevel', $data, $offset)['level'];

        return new DeviceResponses\StatePower($level);
    }

    private function decodeGetService(): DeviceRequests\GetService
    {
        return new DeviceRequests\GetService;
    }

    private function decodeStateService(string $data, int $offset): DeviceResponses\StateService
    {
        [
            'serviceType' => $serviceType,
            'port' => $port,
        ] = \unpack('CserviceType/Vport', $data, $offset);

        return new DeviceResponses\StateService(new DeviceDataTypes\Service($serviceType, $port));
    }

    private function decodeGetVersion(): DeviceRequests\GetVersion
    {
        return new DeviceRequests\GetVersion;
    }

    private function decodeStateVersion(string $data, int $offset): DeviceResponses\StateVersion
    {
        [
            'vendor'  => $vendor,
            'product' => $product,
            'version' => $version,
        ] = \unpack('Vvendor/Vproduct/Vversion', $data, $offset);

        return new DeviceResponses\StateVersion(new DeviceDataTypes\Version($vendor, $product, $version));
    }

    private function decodeGetWifiFirmware(): DeviceRequests\GetWifiFirmware
    {
        return new DeviceRequests\GetWifiFirmware;
    }

    private function decodeStateWifiFirmware(string $data, int $offset): DeviceResponses\StateWifiFirmware
    {
        [
            'build'    => $build,
            'version'  => $version,
        ] = \unpack('Pbuild/Preserved/Vversion', $data, $offset);

        $build = nanotime_to_datetimeimmutable($build);

        return new DeviceResponses\StateWifiFirmware(new DeviceDataTypes\WifiFirmware($build, $version));
    }

    private function decodeGetWifiInfo(): DeviceRequests\GetWifiInfo
    {
        return new DeviceRequests\GetWifiInfo;
    }

    private function decodeStateWifiInfo(string $data, int $offset): DeviceResponses\StateWifiInfo
    {

        [
            'signal'   => $signal,
            'tx'       => $tx,
            'rx'       => $rx,
        ] = \unpack(\DaveRandom\LibLifxLan\FLOAT32_CODE . 'signal/Vtx/Vrx/vreserved', $data, $offset);

        return new DeviceResponses\StateWifiInfo(new DeviceDataTypes\WifiInfo($signal, $tx, $rx));
    }

    private function decodeGet(): LightRequests\Get
    {
        return new LightRequests\Get;
    }

    private function decodeSetColor(string $data, int $offset): LightCommmands\SetColor
    {
        [
            'hue'         => $hue,
            'saturation'  => $saturation,
            'brightness'  => $brightness,
            'temperature' => $temperature,
            'duration'    => $duration,
        ] = \unpack('Creserved/' . self::HSBK_FORMAT . '/Vduration', $data, $offset);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);

        return new LightCommmands\SetColor(new LightDataTypes\ColorTransition($color, $duration));
    }

    private function decodeSetWaveform(string $data, int $offset): LightCommmands\SetWaveform
    {
        [
            'transient' => $transient,
            'hue' => $hue, 'saturation' => $saturation, 'brightness' => $brightness, 'temperature' => $temperature,
            'period' => $period, 'cycles' => $cycles, 'skewRatio' => $skewRatio, 'waveform' => $waveform,
        ] = \unpack(self::SET_WAVEFORM_FORMAT, $data, $offset);

        return new LightCommmands\SetWaveform(new LightDataTypes\Effect(
            (bool)$transient,
            new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature),
            $period, $cycles, uint16_to_int16($skewRatio), $waveform
        ));
    }

    private function decodeSetWaveformOptional(string $data, int $offset): LightCommmands\SetWaveformOptional
    {
        [
            'transient' => $transient,
            'hue' => $hue, 'saturation' => $saturation, 'brightness' => $brightness, 'temperature' => $temperature,
            'period' => $period, 'cycles' => $cycles, 'skewRatio' => $skewRatio, 'waveform' => $waveform,
            'setH' => $setHue, 'setS' => $setSaturation, 'setB' => $setBrightness, 'setK' => $setTemperature,
        ] = \unpack(self::SET_WAVEFORM_FORMAT . '/CsetH/CsetS/CsetB/CsetK', $data, $offset);

        return new LightCommmands\SetWaveformOptional(new LightDataTypes\Effect(
            (bool)$transient,
            new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature),
            $period, $cycles, uint16_to_int16($skewRatio), $waveform,
            $this->createBitField([
                LightDataTypes\Effect::SET_HUE => $setHue,
                LightDataTypes\Effect::SET_SATURATION => $setSaturation,
                LightDataTypes\Effect::SET_BRIGHTNESS => $setBrightness,
                LightDataTypes\Effect::SET_TEMPERATURE => $setTemperature,
            ])
        ));
    }

    private function decodeState(string $data, int $offset): LightResponses\State
    {
        [
            'hue'         => $hue,
            'saturation'  => $saturation,
            'brightness'  => $brightness,
            'temperature' => $temperature,
            'power'       => $power,
            'label'       => $label,
        ] = \unpack(self::HSBK_FORMAT . '/vreserved/vpower/a32label/Preserved', $data, $offset);

        $color = new LightDataTypes\HsbkColor($hue, $saturation, $brightness, $temperature);
        $label = new DeviceDataTypes\Label(\rtrim($label, "\x00"));

        return new LightResponses\State(new LightDataTypes\State($color, $power, $label));
    }

    private function decodeGetInfrared(): LightRequests\GetInfrared
    {
        return new LightRequests\GetInfrared;
    }

    private function decodeSetInfrared(string $data, int $offset): LightCommmands\SetInfrared
    {
        $level = \unpack('vlevel', $data, $offset)['level'];

        return new LightCommmands\SetInfrared($level);
    }

    private function decodeStateInfrared(string $data, int $offset): LightResponses\StateInfrared
    {
        $level = \unpack('vlevel', $data, $offset)['level'];

        return new LightResponses\StateInfrared($level);
    }

    private function decodeGetLightPower(): LightRequests\GetPower
    {
        return new LightRequests\GetPower;
    }

    private function decodeSetLightPower(string $data, int $offset): LightCommmands\SetPower
    {
        [
            'level'    => $level,
            'duration' => $duration,
        ] = \unpack('vlevel/Vduration', $data, $offset);

        return new LightCommmands\SetPower(new LightDataTypes\PowerTransition($level, $duration));
    }

    private function decodeStateLightPower(string $data, int $offset): LightResponses\StatePower
    {
        $level = \unpack('vlevel', $data, $offset)['level'];

        return new LightResponses\StatePower($level);
    }

    public function __construct(UuidFactoryInterface $uuidFactory = null)
    {
        $this->uuidFactory = $uuidFactory ?? new UuidFactory;
    }

    /**
     * @param int $type
     * @param string $data
     * @param int $offset
     * @param int|null $dataLength
     * @return Message
     * @throws DecodingException
     * @throws InvalidMessagePayloadLengthException
     */
    public function decodeMessage(int $type, string $data, int $offset = 0, int $dataLength = null): Message
    {
        $dataLength = $dataLength ?? (\strlen($data) - $offset);

        if (!\array_key_exists($type, self::MESSAGE_INFO)) {
            return new UnknownMessage($type, \substr($data, $offset, $dataLength));
        }

        [$expectedLength, $messageName] = self::MESSAGE_INFO[$type];

        if ($dataLength !== $expectedLength) {
            throw new InvalidMessagePayloadLengthException(
                "Invalid payload length for {$messageName} message,"
                . " expecting {$expectedLength} bytes, got {$dataLength} bytes"
            );
        }

        return ([$this, 'decode' . $messageName])($data, $offset);
    }
}

<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Encoding;

use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\DataTypes as DeviceDataTypes;
use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Messages\Device\Commands as DeviceCommands;
use DaveRandom\LibLifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LibLifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LibLifxLan\Messages\Light\Commands as LightCommands;
use DaveRandom\LibLifxLan\Messages\Light\Requests as LightRequests;
use DaveRandom\LibLifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LibLifxLan\Messages\UnknownMessage;
use DaveRandom\LibLifxLan\Tests\WireData\MessageWireData as Data;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class MessageEncoderTest extends TestCase
{
    public function testEncodeMessageWithUnknownMessage()
    {
        $payload = \random_bytes(64);
        $this->assertSame((new MessageEncoder)->encodeMessage(new UnknownMessage(0, $payload)), $payload);
    }

    public function testEncodeMessageWithSetGroupDeviceCommand()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $group = new DeviceDataTypes\Group($uuid, $label, $updatedAt);
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceCommands\SetGroup($group)), $payload);
        $this->assertSame(DeviceCommands\SetGroup::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetLabelDeviceCommand()
    {
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $payload = Data::LABEL_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceCommands\SetLabel($label)), $payload);
        $this->assertSame(DeviceCommands\SetLabel::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetLocationDeviceCommand()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceCommands\SetLocation($location)), $payload);
        $this->assertSame(DeviceCommands\SetLocation::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetPowerDeviceCommand()
    {
        $level = Data::UINT16_VALUE;
        $payload = Data::UINT16_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceCommands\SetPower($level)), $payload);
        $this->assertSame(DeviceCommands\SetPower::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithEchoRequestDeviceRequest()
    {
        $payload = \random_bytes(64);

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\EchoRequest($payload)), $payload);
        $this->assertSame(DeviceRequests\EchoRequest::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetGroupDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetGroup()), $payload);
        $this->assertSame(DeviceRequests\GetGroup::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetHostFirmwareDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetHostFirmware()), $payload);
        $this->assertSame(DeviceRequests\GetHostFirmware::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetHostInfoDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetHostInfo()), $payload);
        $this->assertSame(DeviceRequests\GetHostInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetInfoDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetInfo()), $payload);
        $this->assertSame(DeviceRequests\GetInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetLabelDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetLabel()), $payload);
        $this->assertSame(DeviceRequests\GetLabel::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetLocationDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetLocation()), $payload);
        $this->assertSame(DeviceRequests\GetLocation::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetPowerDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetPower()), $payload);
        $this->assertSame(DeviceRequests\GetPower::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetServiceDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetService()), $payload);
        $this->assertSame(DeviceRequests\GetService::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetVersionDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetVersion()), $payload);
        $this->assertSame(DeviceRequests\GetVersion::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetWifiFirmwareDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetWifiFirmware()), $payload);
        $this->assertSame(DeviceRequests\GetWifiFirmware::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetWifiInfoDeviceRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetWifiInfo()), $payload);
        $this->assertSame(DeviceRequests\GetWifiInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithAcknowledgementDeviceResponse()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\Acknowledgement()), $payload);
        $this->assertSame(DeviceResponses\Acknowledgement::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithEchoResponseDeviceResponse()
    {
        $payload = \random_bytes(64);

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\EchoResponse($payload)), $payload);
        $this->assertSame(DeviceResponses\EchoResponse::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateGroupDeviceResponse()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $group = new DeviceDataTypes\Group($uuid, $label, $updatedAt);
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateGroup($group)), $payload);
        $this->assertSame(DeviceResponses\StateGroup::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateHostFirmwareDeviceResponse()
    {
        $build = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $firmware = new DeviceDataTypes\HostFirmware($build, Data::UINT32_VALUE);
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateHostFirmware($firmware)), $payload);
        $this->assertSame(DeviceResponses\StateHostFirmware::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateHostInfoDeviceResponse()
    {
        $info = new DeviceDataTypes\HostInfo(Data::FLOAT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateHostInfo($info)), $payload);
        $this->assertSame(DeviceResponses\StateHostInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateInfoDeviceResponse()
    {
        $time = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $info = new DeviceDataTypes\TimeInfo($time, Data::UINT64_VALUE, Data::UINT64_VALUE);
        $payload = Data::DATETIME_BYTES . Data::UINT64_BYTES . Data::UINT64_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateInfo($info)), $payload);
        $this->assertSame(DeviceResponses\StateInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateLabelDeviceResponse()
    {
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $payload = Data::LABEL_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateLabel($label)), $payload);
        $this->assertSame(DeviceResponses\StateLabel::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateLocationDeviceResponse()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateLocation($location)), $payload);
        $this->assertSame(DeviceResponses\StateLocation::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStatePowerDeviceResponse()
    {
        $payload = Data::UINT16_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StatePower(Data::UINT16_VALUE)), $payload);
        $this->assertSame(DeviceResponses\StatePower::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateServiceDeviceResponse()
    {
        $service = new DeviceDataTypes\Service(Data::UINT8_VALUE, Data::UINT32_VALUE);
        $payload = Data::UINT8_BYTES . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateService($service)), $payload);
        $this->assertSame(DeviceResponses\StateService::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateVersionDeviceResponse()
    {
        $version = new DeviceDataTypes\Version(Data::UINT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);
        $payload = Data::UINT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateVersion($version)), $payload);
        $this->assertSame(DeviceResponses\StateVersion::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateWifiFirmwareDeviceResponse()
    {
        $build = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $firmware = new DeviceDataTypes\WifiFirmware($build, Data::UINT32_VALUE);
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateWifiFirmware($firmware)), $payload);
        $this->assertSame(DeviceResponses\StateWifiFirmware::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateWifiInfoDeviceResponse()
    {
        $info = new DeviceDataTypes\WifiInfo(Data::FLOAT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\StateWifiInfo($info)), $payload);
        $this->assertSame(DeviceResponses\StateWifiInfo::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetColorLightCommand()
    {
        [$colorBytes, $color] = Data::generateTestHsbkColor();

        $transition = new LightDataTypes\ColorTransition($color, Data::UINT32_VALUE);
        $payload = Data::reservedBytes(1) . $colorBytes . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetColor($transition)), $payload);
        $this->assertSame(LightCommands\SetColor::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetInfraredLightCommand()
    {
        $payload = Data::UINT16_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetInfrared(Data::UINT16_VALUE)), $payload);
        $this->assertSame(LightCommands\SetInfrared::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetPowerLightCommand()
    {
        $transition = new LightDataTypes\PowerTransition(Data::UINT16_VALUE, Data::UINT32_VALUE);
        $payload = Data::UINT16_BYTES . Data::UINT32_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetPower($transition)), $payload);
        $this->assertSame(LightCommands\SetPower::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithSetWaveformLightCommand()
    {
        foreach (Data::generateTestEffects(false) as $payload => $effect) {
            $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetWaveform($effect)), $payload);
            $this->assertSame(LightCommands\SetWaveform::WIRE_SIZE, \strlen($payload));
        }
    }

    public function testEncodeMessageWithSetWaveformOptionalLightCommand()
    {
        foreach (Data::generateTestEffects(true) as $payload => $effect) {
            $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetWaveformOptional($effect)), $payload);
            $this->assertSame(LightCommands\SetWaveformOptional::WIRE_SIZE, \strlen($payload));
        }
    }

    public function testEncodeMessageWithGetLightRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\Get()), $payload);
        $this->assertSame(LightRequests\Get::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetInfraredLightRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\GetInfrared()), $payload);
        $this->assertSame(LightRequests\GetInfrared::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithGetPowerLightRequest()
    {
        $payload = '';

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\GetPower()), $payload);
        $this->assertSame(LightRequests\GetPower::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateLightResponse()
    {
        [$colorBytes, $color] = Data::generateTestHsbkColor();
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);

        $state = new LightDataTypes\State($color, Data::UINT16_VALUE, $label);
        $payload = $colorBytes . Data::reservedBytes(2) . Data::UINT16_BYTES . Data::LABEL_BYTES . Data::reservedBytes(8);

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightResponses\State($state)), $payload);
        $this->assertSame(LightResponses\State::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStateInfraredLightResponse()
    {
        $payload = Data::UINT16_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightResponses\StateInfrared(Data::UINT16_VALUE)), $payload);
        $this->assertSame(LightResponses\StateInfrared::WIRE_SIZE, \strlen($payload));
    }

    public function testEncodeMessageWithStatePowerLightResponse()
    {
        $payload = Data::UINT16_BYTES;

        $this->assertSame((new MessageEncoder)->encodeMessage(new LightResponses\StatePower(Data::UINT16_VALUE)), $payload);
        $this->assertSame(LightResponses\StatePower::WIRE_SIZE, \strlen($payload));
    }
}

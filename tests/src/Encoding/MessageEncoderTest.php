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
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceCommands\SetGroup($group)),
            Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithSetLabelDeviceCommand()
    {
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceCommands\SetLabel($label)),
            Data::LABEL_BYTES
        );
    }

    public function testEncodeMessageWithSetLocationDeviceCommand()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceCommands\SetLocation($location)),
            Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithSetPowerDeviceCommand()
    {
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceCommands\SetPower(Data::UINT16_VALUE)),
            Data::UINT16_BYTES
        );
    }

    public function testEncodeMessageWithEchoRequestDeviceRequest()
    {
        $payload = \random_bytes(64);
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\EchoRequest($payload)), $payload);
    }

    public function testEncodeMessageWithGetGroupDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetGroup()), '');
    }

    public function testEncodeMessageWithGetHostFirmwareDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetHostFirmware()), '');
    }

    public function testEncodeMessageWithGetHostInfoDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetHostInfo()), '');
    }

    public function testEncodeMessageWithGetInfoDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetInfo()), '');
    }

    public function testEncodeMessageWithGetLabelDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetLabel()), '');
    }

    public function testEncodeMessageWithGetLocationDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetLocation()), '');
    }

    public function testEncodeMessageWithGetPowerDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetPower()), '');
    }

    public function testEncodeMessageWithGetServiceDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetService()), '');
    }

    public function testEncodeMessageWithGetVersionDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetVersion()), '');
    }

    public function testEncodeMessageWithGetWifiFirmwareDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetWifiFirmware()), '');
    }

    public function testEncodeMessageWithGetWifiInfoDeviceRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceRequests\GetWifiInfo()), '');
    }

    public function testEncodeMessageWithAcknowledgementDeviceResponse()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\Acknowledgement()), '');
    }

    public function testEncodeMessageWithEchoResponseDeviceResponse()
    {
        $payload = \random_bytes(64);
        $this->assertSame((new MessageEncoder)->encodeMessage(new DeviceResponses\EchoResponse($payload)), $payload);
    }

    public function testEncodeMessageWithStateGroupDeviceResponse()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $group = new DeviceDataTypes\Group($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateGroup($group)),
            Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithStateHostFirmwareDeviceResponse()
    {
        $build = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);
        $firmware = new DeviceDataTypes\HostFirmware($build, Data::UINT32_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateHostFirmware($firmware)),
            Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithStateHostInfoDeviceResponse()
    {
        $info = new DeviceDataTypes\HostInfo(Data::FLOAT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateHostInfo($info)),
            Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2)
        );
    }

    public function testEncodeMessageWithStateInfoDeviceResponse()
    {
        $time = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);
        $info = new DeviceDataTypes\TimeInfo($time, Data::UINT64_VALUE, Data::UINT64_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateInfo($info)),
            Data::DATETIME_BYTES . Data::UINT64_BYTES . Data::UINT64_BYTES
        );
    }

    public function testEncodeMessageWithStateLabelDeviceResponse()
    {
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceResponses\StateLabel($label)),
            Data::LABEL_BYTES
        );
    }

    public function testEncodeMessageWithStateLocationDeviceResponse()
    {
        $uuid = Uuid::fromBytes(Data::UUID_BYTES);
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateLocation($location)),
            Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithStatePowerDeviceResponse()
    {
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StatePower(Data::UINT16_VALUE)),
            Data::UINT16_BYTES
        );
    }

    public function testEncodeMessageWithStateServiceDeviceResponse()
    {
        $service = new DeviceDataTypes\Service(Data::UINT8_VALUE, Data::UINT32_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateService($service)),
            Data::UINT8_BYTES . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithStateVersionDeviceResponse()
    {
        $version = new DeviceDataTypes\Version(Data::UINT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateVersion($version)),
            Data::UINT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithStateWifiFirmwareDeviceResponse()
    {
        $build = \DateTimeImmutable::createFromFormat(Data::DATETIME_FORMAT, Data::DATETIME_VALUE);
        $firmware = new DeviceDataTypes\WifiFirmware($build, Data::UINT32_VALUE);

        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceResponses\StateWifiFirmware($firmware)),
            Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithStateWifiInfoDeviceResponse()
    {
        $info = new DeviceDataTypes\WifiInfo(Data::FLOAT32_VALUE, Data::UINT32_VALUE, Data::UINT32_VALUE);

        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceResponses\StateWifiInfo($info)),
            Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2)
        );
    }

    public function testEncodeMessageWithSetColorLightCommand()
    {
        [$colorBytes, $color] = Data::generateTestHsbkColor();
        $transition = new LightDataTypes\ColorTransition($color, Data::UINT32_VALUE);

        $this->assertSame((new MessageEncoder)->encodeMessage(
            new LightCommands\SetColor($transition)),
            Data::reservedBytes(1) . $colorBytes . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithSetInfraredLightCommand()
    {
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new LightCommands\SetInfrared(Data::UINT16_VALUE)),
            Data::UINT16_BYTES
        );
    }

    public function testEncodeMessageWithSetPowerLightCommand()
    {
        $transition = new LightDataTypes\PowerTransition(Data::UINT16_VALUE, Data::UINT32_VALUE);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new LightCommands\SetPower($transition)),
            Data::UINT16_BYTES . Data::UINT32_BYTES
        );
    }

    public function testEncodeMessageWithSetWaveformLightCommand()
    {
        foreach (Data::generateTestEffects(false) as $data => $effect) {
            $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetWaveform($effect)), $data);
        }
    }

    public function testEncodeMessageWithSetWaveformOptionalLightCommand()
    {
        foreach (Data::generateTestEffects(true) as $data => $effect) {
            $this->assertSame((new MessageEncoder)->encodeMessage(new LightCommands\SetWaveformOptional($effect)), $data);
        }
    }

    public function testEncodeMessageWithGetLightRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\Get()), '');
    }

    public function testEncodeMessageWithGetInfraredLightRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\GetInfrared()), '');
    }

    public function testEncodeMessageWithGetPowerLightRequest()
    {
        $this->assertSame((new MessageEncoder)->encodeMessage(new LightRequests\GetPower()), '');
    }

    public function testEncodeMessageWithStateLightResponse()
    {
        [$colorBytes, $color] = Data::generateTestHsbkColor();
        $label = new DeviceDataTypes\Label(Data::LABEL_VALUE);

        $state = new LightDataTypes\State($color, Data::UINT16_VALUE, $label);

        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new LightResponses\State($state)),
            $colorBytes . Data::reservedBytes(2) . Data::UINT16_BYTES . Data::LABEL_BYTES . Data::reservedBytes(8)
        );
    }

    public function testEncodeMessageWithStateInfraredLightResponse()
    {
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new LightResponses\StateInfrared(Data::UINT16_VALUE)),
            Data::UINT16_BYTES
        );
    }

    public function testEncodeMessageWithStatePowerLightResponse()
    {
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new LightResponses\StatePower(Data::UINT16_VALUE)),
            Data::UINT16_BYTES
        );
    }
}

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
use DaveRandom\LibLifxLan\Tests\WireData\MessageWireData;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class MessageEncoderTest extends TestCase
{
    public function testEncodeMessageWithSetGroupDeviceCommand()
    {
        $uuid = Uuid::fromBytes(MessageWireData::UUID_BYTES);
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        $group = new DeviceDataTypes\Group($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceCommands\SetGroup($group)),
            MessageWireData::UUID_BYTES . MessageWireData::LABEL_BYTES . MessageWireData::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithSetLabelDeviceCommand()
    {
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceCommands\SetLabel($label)),
            MessageWireData::LABEL_BYTES
        );
    }

    public function testEncodeMessageWithSetLocationDeviceCommand()
    {
        $uuid = Uuid::fromBytes(MessageWireData::UUID_BYTES);
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceCommands\SetLocation($location)),
            MessageWireData::UUID_BYTES . MessageWireData::LABEL_BYTES . MessageWireData::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithSetPowerDeviceCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $levelBytes => $level) {
            $this->assertSame(
                $encoder->encodeMessage(new DeviceCommands\SetPower($level)),
                $levelBytes,
                "level={$level}"
            );
        }
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
        $uuid = Uuid::fromBytes(MessageWireData::UUID_BYTES);
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        $group = new DeviceDataTypes\Group($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateGroup($group)),
            MessageWireData::UUID_BYTES . MessageWireData::LABEL_BYTES . MessageWireData::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithStateHostFirmwareDeviceResponse()
    {
        $encoder = new MessageEncoder();
        $build = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        foreach (MessageWireData::UINT32_TEST_VALUES as $versionBytes => $version) {
            $firmware = new DeviceDataTypes\HostFirmware($build, $version);

            $this->assertSame($encoder->encodeMessage(
                new DeviceResponses\StateHostFirmware($firmware)),
                MessageWireData::DATETIME_BYTES . MessageWireData::reservedBytes(8) . $versionBytes,
                "version={$version}"
            );
        }
    }

    public function testEncodeMessageWithStateHostInfoDeviceResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::FLOAT32_TEST_VALUES as $signalBytes => $signal) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $txBytes => $tx) {
                foreach (MessageWireData::UINT32_TEST_VALUES as $rxBytes => $rx) {
                    $info = new DeviceDataTypes\HostInfo($signal, $tx, $rx);

                    $this->assertSame($encoder->encodeMessage(
                        new DeviceResponses\StateHostInfo($info)),
                        $signalBytes . $txBytes . $rxBytes . MessageWireData::reservedBytes(2),
                        "signal={$signal}, tx={$tx}, rx={$rx}"
                    );
                }
            }
        }
    }

    public function testEncodeMessageWithStateInfoDeviceResponse()
    {
        $encoder = new MessageEncoder();
        $time = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        foreach (MessageWireData::UINT64_TEST_VALUES as $uptimeBytes => $uptime) {
            foreach (MessageWireData::UINT64_TEST_VALUES as $downtimeBytes => $downtime) {
                $info = new DeviceDataTypes\TimeInfo($time, $uptime, $downtime);

                $this->assertSame($encoder->encodeMessage(
                    new DeviceResponses\StateInfo($info)),
                    MessageWireData::DATETIME_BYTES . $uptimeBytes . $downtimeBytes,
                    "uptime={$uptime}, downtime={$downtime}"
                );
            }
        }
    }

    public function testEncodeMessageWithStateLabelDeviceResponse()
    {
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $this->assertSame((new MessageEncoder)->encodeMessage(
            new DeviceResponses\StateLabel($label)),
            MessageWireData::LABEL_BYTES
        );
    }

    public function testEncodeMessageWithStateLocationDeviceResponse()
    {
        $uuid = Uuid::fromBytes(MessageWireData::UUID_BYTES);
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);
        $updatedAt = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        $location = new DeviceDataTypes\Location($uuid, $label, $updatedAt);
        $this->assertSame(
            (new MessageEncoder)->encodeMessage(new DeviceResponses\StateLocation($location)),
            MessageWireData::UUID_BYTES . MessageWireData::LABEL_BYTES . MessageWireData::DATETIME_BYTES
        );
    }

    public function testEncodeMessageWithStatePowerDeviceResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $levelBytes => $level) {
            $this->assertSame(
                $encoder->encodeMessage(new DeviceResponses\StatePower($level)),
                $levelBytes,
                "level={$level}"
            );
        }
    }

    public function testEncodeMessageWithStateServiceDeviceResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT8_TEST_VALUES as $typeIdBytes => $typeId) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $portBytes => $port) {
                $service = new DeviceDataTypes\Service($typeId, $port);

                $this->assertSame(
                    $encoder->encodeMessage(new DeviceResponses\StateService($service)),
                    $typeIdBytes . $portBytes,
                    "typeId={$typeId}, port={$port}"
                );
            }
        }
    }

    public function testEncodeMessageWithStateVersionDeviceResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT32_TEST_VALUES as $vendorBytes => $vendor) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $productBytes => $product) {
                foreach (MessageWireData::UINT32_TEST_VALUES as $versionNoBytes => $versionNo) {
                    $version = new DeviceDataTypes\Version($vendor, $product, $versionNo);

                    $this->assertSame(
                        $encoder->encodeMessage(new DeviceResponses\StateVersion($version)),
                        $vendorBytes . $productBytes . $versionNoBytes,
                        "vendor={$vendor}, product={$product}, version={$versionNo}"
                    );
                }
            }
        }
    }

    public function testEncodeMessageWithStateWifiFirmwareDeviceResponse()
    {
        $encoder = new MessageEncoder();
        $build = \DateTimeImmutable::createFromFormat(MessageWireData::DATETIME_FORMAT, MessageWireData::DATETIME_VALUE);

        foreach (MessageWireData::UINT32_TEST_VALUES as $versionBytes => $version) {
            $firmware = new DeviceDataTypes\WifiFirmware($build, $version);

            $this->assertSame($encoder->encodeMessage(
                new DeviceResponses\StateWifiFirmware($firmware)),
                MessageWireData::DATETIME_BYTES . MessageWireData::reservedBytes(8) . $versionBytes,
                "version={$version}"
            );
        }
    }

    public function testEncodeMessageWithStateWifiInfoDeviceResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::FLOAT32_TEST_VALUES as $signalBytes => $signal) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $txBytes => $tx) {
                foreach (MessageWireData::UINT32_TEST_VALUES as $rxBytes => $rx) {
                    $info = new DeviceDataTypes\WifiInfo($signal, $tx, $rx);

                    $this->assertSame($encoder->encodeMessage(
                        new DeviceResponses\StateWifiInfo($info)),
                        $signalBytes . $txBytes . $rxBytes . MessageWireData::reservedBytes(2),
                        "signal={$signal}, tx={$tx}, rx={$rx}"
                    );
                }
            }
        }
    }

    public function testEncodeMessageWithSetColorLightCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::generateTestHsbkColors() as $colorBytes => $color) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $durationBytes => $duration) {
                $transition = new LightDataTypes\ColorTransition($color, $duration);

                $this->assertSame($encoder->encodeMessage(
                    new LightCommands\SetColor($transition)),
                    MessageWireData::reservedBytes(1) . $colorBytes . $durationBytes,
                    "h={$color->getHue()}, s={$color->getSaturation()}, b={$color->getBrightness()}, k={$color->getTemperature()}, duration={$duration}"
                );
            }
        }
    }

    public function testEncodeMessageWithSetInfraredLightCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $brightnessBytes => $brightness) {
            $this->assertSame(
                $encoder->encodeMessage(new LightCommands\SetInfrared($brightness)),
                $brightnessBytes,
                "brightness={$brightness}"
            );
        }
    }

    public function testEncodeMessageWithSetPowerLightCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $levelBytes => $level) {
            foreach (MessageWireData::UINT32_TEST_VALUES as $durationBytes => $duration) {
                $transition = new LightDataTypes\PowerTransition($level, $duration);

                $this->assertSame(
                    $encoder->encodeMessage(new LightCommands\SetPower($transition)),
                    $levelBytes . $durationBytes,
                    "level={$level}, duration={$duration}"
                );
            }
        }
    }

    public function testEncodeMessageWithSetWaveformLightCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::generateTestEffects(false) as $data => $effect) {
            $this->assertSame($encoder->encodeMessage(new LightCommands\SetWaveform($effect)), $data);
        }
    }

    public function testEncodeMessageWithSetWaveformOptionalLightCommand()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::generateTestEffects(true) as $data => $effect) {
            $this->assertSame($encoder->encodeMessage(new LightCommands\SetWaveformOptional($effect)), $data);
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
        $encoder = new MessageEncoder();
        $label = new DeviceDataTypes\Label(MessageWireData::LABEL_VALUE);

        foreach (MessageWireData::generateTestHsbkColors() as $colorBytes => $color) {
            foreach (MessageWireData::UINT16_TEST_VALUES as $powerBytes => $power) {
                $state = new LightDataTypes\State($color, $power, $label);

                $this->assertSame(
                    $encoder->encodeMessage(new LightResponses\State($state)),
                    $colorBytes . MessageWireData::reservedBytes(2) . $powerBytes . MessageWireData::LABEL_BYTES . MessageWireData::reservedBytes(8),
                    "h={$color->getHue()}, s={$color->getSaturation()}, b={$color->getBrightness()}, k={$color->getTemperature()}, power={$power}"
                );
            }
        }
    }

    public function testEncodeMessageWithStateInfraredLightResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $brightnessBytes => $brightness) {
            $this->assertSame(
                $encoder->encodeMessage(new LightResponses\StateInfrared($brightness)),
                $brightnessBytes,
                "brightness={$brightness}"
            );
        }
    }

    public function testEncodeMessageWithStatePowerLightResponse()
    {
        $encoder = new MessageEncoder();

        foreach (MessageWireData::UINT16_TEST_VALUES as $levelBytes => $level) {
            $this->assertSame(
                $encoder->encodeMessage(new LightResponses\StatePower($level)),
                $levelBytes,
                "level={$level}"
            );
        }
    }
}

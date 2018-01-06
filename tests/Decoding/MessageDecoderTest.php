<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Tests\Decoding;

use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException;
use DaveRandom\LibLifxLan\Decoding\MessageDecoder;
use DaveRandom\LibLifxLan\Messages\Device\Commands as DeviceCommands;
use DaveRandom\LibLifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LibLifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LibLifxLan\Messages\Light\Commands as LightCommands;
use DaveRandom\LibLifxLan\Messages\Light\Requests as LightRequests;
use DaveRandom\LibLifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LibLifxLan\Messages\UnknownMessage;
use DaveRandom\LibLifxLan\Tests\WireData\MessageWireData as Data;
use PHPUnit\Framework\TestCase;

final class MessageDecoderTest extends TestCase
{
    private static function compareFloats(float $a, float $b, int $precision): bool
    {
        [$aCharacteristic, $aMantissa] = \explode('.', (string)$a);
        [$bCharacteristic, $bMantissa] = \explode('.', (string)$b);

        return $aCharacteristic === $bCharacteristic
            && \substr($aMantissa, 0, $precision) === \substr($bMantissa, 0, $precision);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithUnknownMessage(): void
    {
        $payload = \random_bytes(64);

        /** @var UnknownMessage $message */
        $message = (new MessageDecoder)->decodeMessage(0, $payload);

        $this->assertInstanceOf(UnknownMessage::class, $message);

        $this->assertSame($message->getData(), $payload);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetGroupDeviceCommand(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        /** @var DeviceCommands\SetGroup $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceCommands\SetGroup::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceCommands\SetGroup::class, $message);

        $this->assertSame($message->getGroup()->getGuid()->getBytes(), Data::UUID_BYTES);
        $this->assertSame($message->getGroup()->getLabel()->getValue(), Data::LABEL_VALUE);
        $this->assertSame($message->getGroup()->getUpdatedAt()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetGroupDeviceCommandWithDataTooShort(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceCommands\SetGroup::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetLabelDeviceCommand(): void
    {
        $payload = Data::LABEL_BYTES;

        /** @var DeviceCommands\SetLabel $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceCommands\SetLabel::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceCommands\SetLabel::class, $message);

        $this->assertSame($message->getLabel()->getValue(), Data::LABEL_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetLabelDeviceCommandWithDataTooShort(): void
    {
        $payload = Data::LABEL_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceCommands\SetLabel::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetLocationDeviceCommand(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        /** @var DeviceCommands\SetLocation $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceCommands\SetLocation::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceCommands\SetLocation::class, $message);

        $this->assertSame($message->getLocation()->getGuid()->getBytes(), Data::UUID_BYTES);
        $this->assertSame($message->getLocation()->getLabel()->getValue(), Data::LABEL_VALUE);
        $this->assertSame($message->getLocation()->getUpdatedAt()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetLocationDeviceCommandWithDataTooShort(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceCommands\SetLocation::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetPowerDeviceCommand(): void
    {
        $payload = Data::UINT16_BYTES;

        /** @var DeviceCommands\SetPower $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceCommands\SetPower::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceCommands\SetPower::class, $message);

        $this->assertSame($message->getLevel(), Data::UINT16_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetPowerDeviceCommandWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceCommands\SetPower::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithEchoRequestDeviceRequest(): void
    {
        $payload = \random_bytes(64);

        /** @var DeviceRequests\EchoRequest $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\EchoRequest::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceRequests\EchoRequest::class, $message);

        $this->assertSame($message->getPayload(), $payload);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithEchoRequestDeviceRequestWithDataTooShort(): void
    {
        $payload = \random_bytes(64);

        (new MessageDecoder)->decodeMessage(DeviceRequests\EchoRequest::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetGroupDeviceRequest(): void
    {
        /** @var DeviceRequests\GetGroup $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetGroup::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetGroup::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetHostFirmwareDeviceRequest(): void
    {
        /** @var DeviceRequests\GetHostFirmware $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetHostFirmware::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetHostFirmware::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetHostInfoDeviceRequest(): void
    {
        /** @var DeviceRequests\GetHostInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetHostInfo::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetHostInfo::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetInfoDeviceRequest(): void
    {
        /** @var DeviceRequests\GetInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetInfo::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetInfo::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetLabelDeviceRequest(): void
    {
        /** @var DeviceRequests\GetLabel $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetLabel::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetLabel::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetLocationDeviceRequest(): void
    {
        /** @var DeviceRequests\GetLocation $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetLocation::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetLocation::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetPowerDeviceRequest(): void
    {
        /** @var DeviceRequests\GetPower $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetPower::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetPower::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetServiceDeviceRequest(): void
    {
        /** @var DeviceRequests\GetService $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetService::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetService::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetVersionDeviceRequest(): void
    {
        /** @var DeviceRequests\GetVersion $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetVersion::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetVersion::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetWifiFirmwareDeviceRequest(): void
    {
        /** @var DeviceRequests\GetWifiFirmware $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetWifiFirmware::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetWifiFirmware::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetWifiInfoDeviceRequest(): void
    {
        /** @var DeviceRequests\GetWifiInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceRequests\GetWifiInfo::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceRequests\GetWifiInfo::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithAcknowledgementDeviceResponse(): void
    {
        /** @var DeviceResponses\Acknowledgement $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\Acknowledgement::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(DeviceResponses\Acknowledgement::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithEchoResponseDeviceResponse(): void
    {
        $payload = \random_bytes(64);

        /** @var DeviceResponses\EchoResponse $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\EchoResponse::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\EchoResponse::class, $message);

        $this->assertSame($message->getPayload(), $payload);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithEchoResponseDeviceResponseWithDataTooShort(): void
    {
        $payload = \random_bytes(64);

        (new MessageDecoder)->decodeMessage(DeviceResponses\EchoResponse::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateGroupDeviceResponse(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        /** @var DeviceResponses\StateGroup $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateGroup::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateGroup::class, $message);

        $this->assertSame($message->getGroup()->getGuid()->getBytes(), Data::UUID_BYTES);
        $this->assertSame($message->getGroup()->getLabel()->getValue(), Data::LABEL_VALUE);
        $this->assertSame($message->getGroup()->getUpdatedAt()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateGroupDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateGroup::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateHostFirmwareDeviceResponse(): void
    {
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        /** @var DeviceResponses\StateHostFirmware $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateHostFirmware::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateHostFirmware::class, $message);

        $this->assertSame($message->getHostFirmware()->getBuild()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
        $this->assertSame($message->getHostFirmware()->getVersion(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateHostFirmwareDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateHostFirmware::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateHostInfoDeviceResponse(): void
    {
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        /** @var DeviceResponses\StateHostInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateHostInfo::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateHostInfo::class, $message);

        $this->assertTrue(self::compareFloats($message->getHostInfo()->getSignal(), Data::FLOAT32_VALUE, 3));
        $this->assertSame($message->getHostInfo()->getTx(), Data::UINT32_VALUE);
        $this->assertSame($message->getHostInfo()->getRx(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateHostInfoDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateHostInfo::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateInfoDeviceResponse(): void
    {
        $payload = Data::DATETIME_BYTES . Data::UINT64_BYTES . Data::UINT64_BYTES;

        /** @var DeviceResponses\StateInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateInfo::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateInfo::class, $message);

        $this->assertSame($message->getInfo()->getTime()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
        $this->assertSame($message->getInfo()->getUptime(), Data::UINT64_VALUE);
        $this->assertSame($message->getInfo()->getDowntime(), Data::UINT64_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateInfoDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::DATETIME_BYTES . Data::UINT64_BYTES . Data::UINT64_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateInfo::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLabelDeviceResponse(): void
    {
        $payload = Data::LABEL_BYTES;

        /** @var DeviceResponses\StateLabel $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateLabel::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateLabel::class, $message);

        $this->assertSame($message->getLabel()->getValue(), Data::LABEL_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLabelDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::LABEL_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateLabel::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLocationDeviceResponse(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        /** @var DeviceResponses\StateLocation $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateLocation::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateLocation::class, $message);

        $this->assertSame($message->getLocation()->getGuid()->getBytes(), Data::UUID_BYTES);
        $this->assertSame($message->getLocation()->getLabel()->getValue(), Data::LABEL_VALUE);
        $this->assertSame($message->getLocation()->getUpdatedAt()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLocationDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::UUID_BYTES . Data::LABEL_BYTES . Data::DATETIME_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateLocation::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStatePowerDeviceResponse(): void
    {
        $payload = Data::UINT16_BYTES;

        /** @var DeviceResponses\StatePower $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StatePower::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StatePower::class, $message);

        $this->assertSame($message->getLevel(), Data::UINT16_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStatePowerDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StatePower::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws \DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException
     */
    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateServiceDeviceResponse(): void
    {
        $payload = Data::UINT8_BYTES . Data::UINT32_BYTES;

        /** @var DeviceResponses\StateService $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateService::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateService::class, $message);

        $this->assertSame($message->getService()->getTypeId(), Data::UINT8_VALUE);
        $this->assertSame($message->getService()->getPort(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateServiceDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::UINT8_BYTES . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateService::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateVersionDeviceResponse(): void
    {
        $payload = Data::UINT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES;

        /** @var DeviceResponses\StateVersion $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateVersion::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateVersion::class, $message);

        $this->assertSame($message->getVersion()->getVendor(), Data::UINT32_VALUE);
        $this->assertSame($message->getVersion()->getProduct(), Data::UINT32_VALUE);
        $this->assertSame($message->getVersion()->getVersion(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateVersionDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::UINT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateVersion::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateWifiFirmwareDeviceResponse(): void
    {
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        /** @var DeviceResponses\StateWifiFirmware $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateWifiFirmware::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateWifiFirmware::class, $message);

        $this->assertSame($message->getWifiFirmware()->getBuild()->format(Data::DATETIME_FORMAT), Data::DATETIME_VALUE);
        $this->assertSame($message->getWifiFirmware()->getVersion(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateWifiFirmwareDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::DATETIME_BYTES . Data::reservedBytes(8) . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateWifiFirmware::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateWifiInfoDeviceResponse(): void
    {
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        /** @var DeviceResponses\StateWifiInfo $message */
        $message = (new MessageDecoder)->decodeMessage(DeviceResponses\StateWifiInfo::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(DeviceResponses\StateWifiInfo::class, $message);

        $this->assertTrue(self::compareFloats($message->getWifiInfo()->getSignal(), Data::FLOAT32_VALUE, 3));
        $this->assertSame($message->getWifiInfo()->getTx(), Data::UINT32_VALUE);
        $this->assertSame($message->getWifiInfo()->getRx(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateWifiInfoDeviceResponseWithDataTooShort(): void
    {
        $payload = Data::FLOAT32_BYTES . Data::UINT32_BYTES . Data::UINT32_BYTES . Data::reservedBytes(2);

        (new MessageDecoder)->decodeMessage(DeviceResponses\StateWifiInfo::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetColorLightCommand(): void
    {
        /** @var LightDataTypes\HsbkColor $color */
        [$colorBytes, $color] = Data::generateTestHsbkColor();

        $payload = Data::reservedBytes(1) . $colorBytes . Data::UINT32_BYTES;

        /** @var LightCommands\SetColor $message */
        $message = (new MessageDecoder)->decodeMessage(LightCommands\SetColor::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightCommands\SetColor::class, $message);

        $this->assertSame($message->getColorTransition()->getColor()->getHue(), $color->getHue());
        $this->assertSame($message->getColorTransition()->getColor()->getSaturation(), $color->getSaturation());
        $this->assertSame($message->getColorTransition()->getColor()->getBrightness(), $color->getBrightness());
        $this->assertSame($message->getColorTransition()->getColor()->getTemperature(), $color->getTemperature());
        $this->assertSame($message->getColorTransition()->getDuration(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetColorLightCommandWithDataTooShort(): void
    {
        [$colorBytes] = Data::generateTestHsbkColor();

        $payload = Data::reservedBytes(1) . $colorBytes . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(LightCommands\SetColor::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetInfraredLightCommand(): void
    {
        $payload = Data::UINT16_BYTES;

        /** @var LightCommands\SetInfrared $message */
        $message = (new MessageDecoder)->decodeMessage(LightCommands\SetInfrared::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightCommands\SetInfrared::class, $message);

        $this->assertSame($message->getBrightness(), Data::UINT16_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetInfraredLightCommandWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES;

        (new MessageDecoder)->decodeMessage(LightCommands\SetInfrared::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetPowerLightCommand(): void
    {
        $payload = Data::UINT16_BYTES . Data::UINT32_BYTES;

        /** @var LightCommands\SetPower $message */
        $message = (new MessageDecoder)->decodeMessage(LightCommands\SetPower::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightCommands\SetPower::class, $message);

        $this->assertSame($message->getPowerTransition()->getLevel(), Data::UINT16_VALUE);
        $this->assertSame($message->getPowerTransition()->getDuration(), Data::UINT32_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetPowerLightCommandWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES . Data::UINT32_BYTES;

        (new MessageDecoder)->decodeMessage(LightCommands\SetPower::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetWaveformLightCommand(): void
    {
        /** @var LightDataTypes\Effect $effect */
        foreach (Data::generateTestEffects(false) as $payload => $effect) {
            /** @var LightCommands\SetWaveform $message */
            $message = (new MessageDecoder)->decodeMessage(LightCommands\SetWaveform::MESSAGE_TYPE_ID, $payload);

            $this->assertInstanceOf(LightCommands\SetWaveform::class, $message);

            $this->assertSame($message->getEffect()->isTransient(), $effect->isTransient());
            $this->assertSame($message->getEffect()->getColor()->getHue(), $effect->getColor()->getHue());
            $this->assertSame($message->getEffect()->getColor()->getSaturation(), $effect->getColor()->getSaturation());
            $this->assertSame($message->getEffect()->getColor()->getBrightness(), $effect->getColor()->getBrightness());
            $this->assertSame($message->getEffect()->getColor()->getTemperature(), $effect->getColor()->getTemperature());
            $this->assertSame($message->getEffect()->getPeriod(), $effect->getPeriod());
            $this->assertTrue(self::compareFloats($message->getEffect()->getCycles(), $effect->getCycles(), 3));
            $this->assertSame($message->getEffect()->getSkewRatio(), $effect->getSkewRatio());
            $this->assertSame($message->getEffect()->getWaveform(), $effect->getWaveform());
        }
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetWaveformLightCommandWithDataTooShort(): void
    {
        $i = 0;
        $failures = 0;

        foreach (Data::generateTestEffects(false) as $payload => $effect) {
            $i++;

            try {
                (new MessageDecoder)->decodeMessage(LightCommands\SetWaveform::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
            } catch (InvalidMessagePayloadLengthException $e) {
                $failures++;
            }
        }

        $this->assertSame($i, $failures);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetWaveformOptionalLightCommand(): void
    {
        /** @var LightDataTypes\Effect $effect */
        foreach (Data::generateTestEffects(true) as $payload => $effect) {
            /** @var LightCommands\SetWaveformOptional $message */
            $message = (new MessageDecoder)->decodeMessage(LightCommands\SetWaveformOptional::MESSAGE_TYPE_ID, $payload);

            $this->assertInstanceOf(LightCommands\SetWaveformOptional::class, $message);

            $this->assertSame($message->getEffect()->isTransient(), $effect->isTransient());
            $this->assertSame($message->getEffect()->getColor()->getHue(), $effect->getColor()->getHue());
            $this->assertSame($message->getEffect()->getColor()->getSaturation(), $effect->getColor()->getSaturation());
            $this->assertSame($message->getEffect()->getColor()->getBrightness(), $effect->getColor()->getBrightness());
            $this->assertSame($message->getEffect()->getColor()->getTemperature(), $effect->getColor()->getTemperature());
            $this->assertSame($message->getEffect()->getPeriod(), $effect->getPeriod());
            $this->assertTrue(self::compareFloats($message->getEffect()->getCycles(), $effect->getCycles(), 3));
            $this->assertSame($message->getEffect()->getSkewRatio(), $effect->getSkewRatio());
            $this->assertSame($message->getEffect()->getWaveform(), $effect->getWaveform());
            $this->assertSame($message->getEffect()->getOptions(), $effect->getOptions());
        }
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithSetWaveformOptionalLightCommandWithDataTooShort(): void
    {
        $i = 0;
        $failures = 0;

        foreach (Data::generateTestEffects(true) as $payload => $effect) {
            $i++;

            try {
                (new MessageDecoder)->decodeMessage(LightCommands\SetWaveformOptional::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
            } catch (InvalidMessagePayloadLengthException $e) {
                $failures++;
            }
        }

        $this->assertSame($i, $failures);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetLightRequest(): void
    {
        /** @var LightRequests\Get $message */
        $message = (new MessageDecoder)->decodeMessage(LightRequests\Get::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(LightRequests\Get::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetInfraredLightRequest(): void
    {
        /** @var LightRequests\GetInfrared $message */
        $message = (new MessageDecoder)->decodeMessage(LightRequests\GetInfrared::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(LightRequests\GetInfrared::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithGetPowerLightRequest(): void
    {
        /** @var LightRequests\GetPower $message */
        $message = (new MessageDecoder)->decodeMessage(LightRequests\GetPower::MESSAGE_TYPE_ID, '');

        $this->assertInstanceOf(LightRequests\GetPower::class, $message);
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLightResponse(): void
    {
        /** @var LightDataTypes\HsbkColor $color */
        [$colorBytes, $color] = Data::generateTestHsbkColor();

        $payload = $colorBytes . Data::reservedBytes(2) . Data::UINT16_BYTES . Data::LABEL_BYTES . Data::reservedBytes(8);

        /** @var LightResponses\State $message */
        $message = (new MessageDecoder)->decodeMessage(LightResponses\State::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightResponses\State::class, $message);

        $this->assertSame($message->getState()->getColor()->getHue(), $color->getHue());
        $this->assertSame($message->getState()->getColor()->getSaturation(), $color->getSaturation());
        $this->assertSame($message->getState()->getColor()->getBrightness(), $color->getBrightness());
        $this->assertSame($message->getState()->getColor()->getTemperature(), $color->getTemperature());
        $this->assertSame($message->getState()->getPower(), Data::UINT16_VALUE);
        $this->assertSame($message->getState()->getLabel()->getValue(), Data::LABEL_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateLightResponseWithDataTooShort(): void
    {
        /** @var LightDataTypes\HsbkColor $color */
        [$colorBytes] = Data::generateTestHsbkColor();

        $payload = $colorBytes . Data::reservedBytes(2) . Data::UINT16_BYTES . Data::LABEL_BYTES . Data::reservedBytes(8);

        (new MessageDecoder)->decodeMessage(LightResponses\State::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateInfraredLightResponse(): void
    {
        $payload = Data::UINT16_BYTES;

        /** @var LightResponses\StateInfrared $message */
        $message = (new MessageDecoder)->decodeMessage(LightResponses\StateInfrared::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightResponses\StateInfrared::class, $message);

        $this->assertSame($message->getBrightness(), Data::UINT16_VALUE);
    }

    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStateInfraredLightResponseWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES;

        (new MessageDecoder)->decodeMessage(LightResponses\StateInfrared::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }

    /**
     * @throws DecodingException
     */
    public function testDecodeMessageWithStatePowerLightResponse(): void
    {
        $payload = Data::UINT16_BYTES;

        /** @var LightResponses\StatePower $message */
        $message = (new MessageDecoder)->decodeMessage(LightResponses\StatePower::MESSAGE_TYPE_ID, $payload);

        $this->assertInstanceOf(LightResponses\StatePower::class, $message);

        $this->assertSame($message->getLevel(), Data::UINT16_VALUE);
    }
    /**
     * @expectedException \DaveRandom\LibLifxLan\Decoding\Exceptions\InvalidMessagePayloadLengthException
     * @throws DecodingException
     */
    public function testDecodeMessageWithStatePowerLightResponseWithDataTooShort(): void
    {
        $payload = Data::UINT16_BYTES;

        (new MessageDecoder)->decodeMessage(LightResponses\StatePower::MESSAGE_TYPE_ID, \substr($payload, 0, -1));
    }
}

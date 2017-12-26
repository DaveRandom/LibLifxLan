<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\DataTypes as DeviceDataTypes;
use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageException;
use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use const DaveRandom\LibLifxLan\FLOAT32_CODE;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Header\FrameAddress;
use DaveRandom\LibLifxLan\Header\Header;
use DaveRandom\LibLifxLan\Header\ProtocolHeader;
use DaveRandom\LibLifxLan\Messages\Device\Commands as DeviceCommands;
use DaveRandom\LibLifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LibLifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LibLifxLan\Messages\Light\Commands as LightCommmands;
use DaveRandom\LibLifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\Network\MacAddress;

final class MessageEncoder extends Encoder
{
    /**
     * @uses encodeEchoRequest
     * @uses encodeEchoResponse
     * @uses encodeSetGroup
     * @uses encodeStateGroup
     * @uses encodeStateHostFirmware
     * @uses encodeStateHostInfo
     * @uses encodeStateInfo
     * @uses encodeSetLabel
     * @uses encodeStateLabel
     * @uses encodeSetLocation
     * @uses encodeStateLocation
     * @uses encodeSetDevicePower
     * @uses encodeStateDevicePower
     * @uses encodeStateService
     * @uses encodeStateVersion
     * @uses encodeStateWifiFirmware
     * @uses encodeStateWifiInfo
     * @uses encodeSetColor
     * @uses encodeSetWaveform
     * @uses encodeSetWaveformOptional
     * @uses encodeState
     * @uses encodeSetInfrared
     * @uses encodeStateInfrared
     * @uses encodeSetLightPower
     * @uses encodeStateLightPower
     */
    private const ENCODING_ROUTINES = [
        // Device command messages
        DeviceCommands\SetGroup::class => 'SetGroup',
        DeviceCommands\SetLabel::class => 'SetLabel',
        DeviceCommands\SetLocation::class => 'SetLocation',
        DeviceCommands\SetPower::class => 'SetDevicePower',

        // Device request messages
        DeviceRequests\EchoRequest::class => 'EchoRequest',

        // Device response messages
        DeviceResponses\EchoResponse::class => 'EchoResponse',
        DeviceResponses\StateGroup::class => 'StateGroup',
        DeviceResponses\StateHostFirmware::class => 'StateHostFirmware',
        DeviceResponses\StateHostInfo::class => 'StateHostInfo',
        DeviceResponses\StateInfo::class => 'StateInfo', // todo
        DeviceResponses\StateLabel::class => 'StateLabel', // todo
        DeviceResponses\StateLocation::class => 'StateLocation',
        DeviceResponses\StatePower::class => 'StateDevicePower', // todo
        DeviceResponses\StateService::class => 'StateService', // todo
        DeviceResponses\StateVersion::class => 'StateVersion', // todo
        DeviceResponses\StateWifiFirmware::class => 'StateWifiFirmware',
        DeviceResponses\StateWifiInfo::class => 'StateWifiInfo',

        // Light command messages
        LightCommmands\SetColor::class => 'SetColor', // todo
        LightCommmands\SetInfrared::class => 'SetInfrared', // todo
        LightCommmands\SetPower::class => 'SetLightPower', // todo
        LightCommmands\SetWaveform::class => 'SetWaveform', // todo
        LightCommmands\SetWaveformOptional::class => 'SetWaveformOptional', // todo

        // Light response messages
        LightResponses\State::class => 'State', // todo
        LightResponses\StateInfrared::class => 'StateInfrared', // todo
        LightResponses\StatePower::class => 'StateLightPower', // todo
    ];

    public const DEFAULT_SOURCE_ID = 0x0da7e51d;
    public const DEFAULT_MESSAGE_ORIGIN = 0;
    public const DEFAULT_PROTOCOL_NUMBER = 1024;

    public const OP_SOURCE_ID       = 1;
    public const OP_MESSAGE_ORIGIN  = 2;
    public const OP_PROTOCOL_NUMBER = 3;

    private $headerEncoder;

    private function encodeHsbkColor(LightDataTypes\HsbkColor $color): string
    {
        return \pack('v4', $color->getHue(), $color->getSaturation(), $color->getBrightness(), $color->getTemperature());
    }

    /**
     * @param string $label
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeLabel(string $label): string
    {
        if (\strlen($label) > 32) {
            throw new InvalidMessageException("Label cannot be larger than 32 bytes");
        }

        return \pack('a32', $label);
    }

    /**
     * @param DeviceDataTypes\Location $location
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeLocation(DeviceDataTypes\Location $location): string
    {
        $updatedAt = $this->dateTimeToNanoseconds($location->getUpdatedAt());

        if ($updatedAt < 0) {
            throw new InvalidMessageException("Updated at timestamp {$updatedAt} is negative");
        }

        $guid = $location->getGuid()->getBytes();
        $label = $this->encodeLabel($location->getLabel());

        return $guid . $label . \pack('P', $updatedAt);
    }

    /**
     * @param DeviceDataTypes\Group $group
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeGroup(DeviceDataTypes\Group $group): string
    {
        $updatedAt = $this->dateTimeToNanoseconds($group->getUpdatedAt());

        if ($updatedAt < 0) {
            throw new InvalidMessageException("Updated at timestamp {$updatedAt} is negative");
        }

        $guid = $group->getGuid()->getBytes();
        $label = $this->encodeLabel($group->getLabel());

        return $guid . $label . \pack('P', $updatedAt);
    }

    private function encodeFirmware(DeviceDataTypes\Firmware $firmware)
    {
        return \pack(
            'PPV',
            $this->dateTimeToNanoseconds($firmware->getBuild()),
            0, // reserved
            $firmware->getVersion()
        );
    }

    private function encodeNetworkInfo(DeviceDataTypes\NetworkInfo $info)
    {
        return \pack(
            FLOAT32_CODE . 'VVv',
            $info->getSignal(),
            $info->getTx(),
            $info->getRx(),
            0 // reserved
        );
    }

    private function dateTimeToNanoseconds(\DateTimeInterface $dateTime): int
    {
        return ($dateTime->format('U') * 1000000000) + ($dateTime->format('u') * 1000);
    }

    /**
     * @param Message $message
     * @param MacAddress|null $destination
     * @param int $sequenceNo
     * @param string $payload
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function buildPacket(Message $message, ?MacAddress $destination, int $sequenceNo, string $payload): string
    {
        $frame = new Frame(
            Header::WIRE_SIZE + \strlen($payload),
            $this->options[self::OP_MESSAGE_ORIGIN],
            /* tagged */ $destination === null,
            /* addressable */ true,
            $this->options[self::OP_PROTOCOL_NUMBER],
            $this->options[self::OP_SOURCE_ID]
        );

        $frameAddress = new FrameAddress($destination, $message->isAckRequired(), $message->isResponseRequired(), $sequenceNo);
        $protocolHeader = new ProtocolHeader($message->getTypeId());

        return $this->headerEncoder->encodeHeader(new Header($frame, $frameAddress, $protocolHeader)) . $payload;
    }

    public function __construct(array $options = [], HeaderEncoder $headerEncoder = null)
    {
        parent::__construct($options + [
            self::OP_SOURCE_ID => self::DEFAULT_SOURCE_ID,
            self::OP_MESSAGE_ORIGIN => self::DEFAULT_MESSAGE_ORIGIN,
            self::OP_PROTOCOL_NUMBER => self::DEFAULT_PROTOCOL_NUMBER,
        ]);

        $this->headerEncoder = $headerEncoder ?? new HeaderEncoder;
    }

    private function encodeStateHostFirmware(DeviceResponses\StateHostFirmware $message): string
    {
        return $this->encodeFirmware($message->getHostFirmware());
    }

    private function encodeStateHostInfo(DeviceResponses\StateHostInfo $message): string
    {
        return $this->encodeNetworkInfo($message->getHostInfo());
    }

    private function encodeStateWifiFirmware(DeviceResponses\StateWifiFirmware $message): string
    {
        return $this->encodeFirmware($message->getWifiFirmware());
    }

    private function encodeStateWifiInfo(DeviceResponses\StateWifiInfo $message): string
    {
        return $this->encodeNetworkInfo($message->getWifiInfo());
    }

    /**
     * @param DeviceCommands\SetGroup $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetGroup(DeviceCommands\SetGroup $message): string
    {
        return $this->encodeGroup($message->getGroup());
    }

    /**
     * @param DeviceResponses\StateGroup $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeStateGroup(DeviceResponses\StateGroup $message): string
    {
        return $this->encodeGroup($message->getGroup());
    }

    /**
     * @param DeviceCommands\SetLabel $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetLabel(DeviceCommands\SetLabel $message): string
    {
        return $this->encodeLabel($message->getLabel());
    }

    /**
     * @param DeviceCommands\SetLocation $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetLocation(DeviceCommands\SetLocation $message): string
    {
        return $this->encodeLocation($message->getLocation());
    }

    /**
     * @param DeviceResponses\StateLocation $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeStateLocation(DeviceResponses\StateLocation $message): string
    {
        return $this->encodeLocation($message->getLocation());
    }

    /**
     * @param DeviceCommands\SetPower $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetDevicePower(DeviceCommands\SetPower $message): string
    {
        $level = $message->getLevel();

        if ($level < 0 || $level > 65535) {
            throw new InvalidMessageException("Power level {$level} outside allowable range of 0 - 65535");
        }

        return \pack('v', $level);
    }

    /**
     * @param DeviceRequests\EchoRequest $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeEchoRequest(DeviceRequests\EchoRequest $message): string
    {
        $payload = $message->getPayload();

        if (\strlen($payload) !== 64) {
            throw new InvalidMessageException("Echo request payload should be exactly 64 bytes");
        }

        return $payload;
    }

    /**
     * @param DeviceResponses\EchoResponse $message
     * @return string
     */
    private function encodeEchoResponse(DeviceResponses\EchoResponse $message): string
    {
        return $message->getPayload(); // don't validate this as it should respond with client data verbatim
    }

    /**
     * @param LightCommmands\SetColor $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetColor(LightCommmands\SetColor $message): string
    {
        $transition = $message->getColorTransition();
        $duration = $transition->getDuration();

        if ($duration < 0 || $duration > 4294967295) {
            throw new InvalidMessageException("Transition duration {$duration} outside allowable range of 0 - 4294967295");
        }

        return "\x00" . $this->encodeHsbkColor($transition->getColor()) . \pack('V', $duration);
    }

    /**
     * @param LightCommmands\SetPower $message
     * @return string
     * @throws InvalidMessageException
     */
    private function encodeSetLightPower(LightCommmands\SetPower $message): string
    {
        $transition = $message->getPowerTransition();
        $level = $transition->getLevel();
        $duration = $transition->getDuration();

        if ($level < 0 || $level > 65535) {
            throw new InvalidMessageException("Power level {$level} outside allowable range of 0 - 65535");
        }

        if ($duration < 0 || $duration > 4294967295) {
            throw new InvalidMessageException("Transition duration {$duration} outside allowable range of 0 - 4294967295");
        }

        return \pack('vV', $level, $duration);
    }

    /**
     * @param Message $message
     * @param MacAddress|null $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodeMessage(Message $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $payload = \array_key_exists($class = \get_class($message), self::ENCODING_ROUTINES)
            ? $this->{'encode' . self::ENCODING_ROUTINES[$class]}($message)
            : '';

        return $this->buildPacket($message, $destination, $sequenceNo, $payload);
    }
}

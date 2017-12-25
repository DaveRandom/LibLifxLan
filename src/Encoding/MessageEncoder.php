<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Encoding;

use DaveRandom\LifxLan\Encoding\Exceptions\InvalidMessageException;
use DaveRandom\LifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LifxLan\Header\Frame;
use DaveRandom\LifxLan\Header\FrameAddress;
use DaveRandom\LifxLan\Header\Header;
use DaveRandom\LifxLan\Header\ProtocolHeader;
use DaveRandom\LifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LifxLan\Messages\Device\Commands\SetGroup;
use DaveRandom\LifxLan\Messages\Device\Commands\SetLabel;
use DaveRandom\LifxLan\Messages\Device\Commands\SetLocation;
use DaveRandom\LifxLan\Messages\Device\Commands\SetPower as SetDevicePower;
use DaveRandom\LifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LifxLan\Messages\Light\Commands\SetPower as SetLightPower;
use DaveRandom\LifxLan\Messages\Device\Requests\EchoRequest;
use DaveRandom\LifxLan\Messages\Device\Requests\GetService;
use DaveRandom\LifxLan\Messages\Message;
use DaveRandom\LifxLan\Network\MacAddress;

final class MessageEncoder extends Encoder
{
    public const DEFAULT_SOURCE_ID = 0x0da7e51d;
    public const DEFAULT_MESSAGE_ORIGIN = 0;
    public const DEFAULT_PROTOCOL_NUMBER = 1024;

    public const OP_SOURCE_ID       = 1;
    public const OP_MESSAGE_ORIGIN  = 2;
    public const OP_PROTOCOL_NUMBER = 3;

    private $headerEncoder;

    private function encodeHsbkColor(HsbkColor $color): string
    {
        return \pack('v4', $color->getHue(), $color->getSaturation(), $color->getBrightness(), $color->getTemperature());
    }

    private function createHeader(Message $message, ?MacAddress $destination, int $sequenceNo, int $payloadSize = 0): Header
    {
        $frame = new Frame(
            Header::WIRE_SIZE + $payloadSize,
            $this->options[self::OP_MESSAGE_ORIGIN],
            /* tagged */ $destination === null,
            /* addressable */ true,
            $this->options[self::OP_PROTOCOL_NUMBER],
            $this->options[self::OP_SOURCE_ID]
        );

        $frameAddress = new FrameAddress($destination, $message->isAckRequired(), $message->isResponseRequired(), $sequenceNo);
        $protocolHeader = new ProtocolHeader($message->getTypeId());

        return new Header($frame, $frameAddress, $protocolHeader);
    }

    /**
     * @param Message $message
     * @param MacAddress|null $destination
     * @param int $sequenceNo
     * @param string $payload
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function encodeMessage(Message $message, ?MacAddress $destination, int $sequenceNo, string $payload): string
    {
        $header = $this->createHeader($message, $destination, $sequenceNo, \strlen($payload));

        return $this->headerEncoder->encodeHeader($header) . $payload;
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

    /**
     * @param EchoRequest $message
     * @param MacAddress|null $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeEchoRequest(EchoRequest $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $payload = $message->getPayload();
        $length = \strlen($payload);

        if ($length > 64) {
            throw new InvalidMessageException("Echo request payload exceeds maximum allowed size of 64 bytes");
        }

        return $this->encodeMessage($message, $destination, $sequenceNo, \str_pad($payload, 64, "\x00", \STR_PAD_RIGHT));
    }

    /**
     * @param GetService $message
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeGetServiceMessage(GetService $message, int $sequenceNo = 0): string
    {
        return $this->encodeMessage($message, null, $sequenceNo, '');
    }

    /**
     * @param Message $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeMessageWithNoPayload(Message $message, ?MacAddress $destination, int $sequenceNo): string
    {
        return $this->encodeMessage($message, $destination, $sequenceNo, '');
    }

    /**
     * @param SetGroup $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetGroupMessage(SetGroup $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $group = $message->getGroup();
        $guid = $group->getGuid();
        $label = $group->getLabel();
        $updatedAt = $group->getUpdatedAt();

        if (\strlen($label) > 32) {
            throw new InvalidMessageException("Label cannot be larger than 32 bytes");
        }

        if ($updatedAt < 0) { // do not validate upper end because max value is PHP_INT_MAX
            throw new InvalidMessageException("Updated at timestamp {$updatedAt} is negative");
        }

        return $this->encodeMessage($message, $destination, $sequenceNo, \pack('a16a32P', $guid->getBytes(), $label, $updatedAt));
    }

    /**
     * @param SetLabel $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetLabelMessage(SetLabel $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $label = $message->getLabel();

        if (\strlen($label) > 32) {
            throw new InvalidMessageException("Label cannot be larger than 32 bytes");
        }

        return $this->encodeMessage($message, $destination, $sequenceNo, \pack('a32', $label));
    }

    /**
     * @param SetLocation $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetLocationMessage(SetLocation $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $location = $message->getLocation();
        $guid = $location->getGuid();
        $label = $location->getLabel();
        $updatedAt = $location->getUpdatedAt();

        if (\strlen($label) > 32) {
            throw new InvalidMessageException("Label cannot be larger than 32 bytes");
        }

        if ($updatedAt < 0) { // do not validate upper end because max value is PHP_INT_MAX
            throw new InvalidMessageException("Updated at timestamp {$updatedAt} is negative");
        }

        return $this->encodeMessage($message, $destination, $sequenceNo, \pack('a16a32P', $guid->getBytes(), $label, $updatedAt));
    }

    /**
     * @param SetDevicePower $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetDevicePowerMessage(SetDevicePower $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $level = $message->getLevel();

        if ($level < 0 || $level > 65535) {
            throw new InvalidMessageException("Power level {$level} outside allowable range of 0 - 65535");
        }

        return $this->encodeMessage($message, $destination, $sequenceNo, \pack('v', $level));
    }

    /**
     * @param SetColor $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetColorMessage(SetColor $message, ?MacAddress $destination, int $sequenceNo): string
    {
        $transition = $message->getColorTransition();
        $color = $transition->getColor();
        $duration = $transition->getDuration();

        if ($duration < 0 || $duration > 4294967295) {
            throw new InvalidMessageException("Transition duration {$duration} outside allowable range of 0 - 4294967295");
        }

        $payload = "\x00" . $this->encodeHsbkColor($color) . \pack('V', $duration);

        return $this->encodeMessage($message, $destination, $sequenceNo, $payload);
    }

    /**
     * @param SetLightPower $message
     * @param MacAddress $destination
     * @param int $sequenceNo
     * @return string
     * @throws InvalidMessageException
     */
    public function encodeSetLightPowerMessage(SetLightPower $message, ?MacAddress $destination, int $sequenceNo): string
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

        return $this->encodeMessage($message, $destination, $sequenceNo, \pack('vV', $level, $duration));
    }
}

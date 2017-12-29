<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageHeaderException;
use DaveRandom\LibLifxLan\Header\Frame;
use DaveRandom\LibLifxLan\Options;

final class FrameEncoder
{
    use Options;

    /**
     * If enabled, allow origin values other than 0
     */
    public const OP_ALLOW_ORIGIN_VARIANCE = 0b01;

    /**
     * If enabled, allow protocol numbers other than 1024
     */
    public const OP_ALLOW_PROTOCOL_NUMBER_VARIANCE = 0b10;

    /**
     * @param Frame $frame
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function encodeMessageSize(Frame $frame): string
    {
        $size = $frame->getSize();

        if ($size < 0 || $size > 65535) {
            throw new InvalidMessageHeaderException("Message size value {$size} outside allowable range 0 - 65535");
        }

        return \pack('v', $size);
    }

    /**
     * @param Frame $frame
     * @return int
     * @throws InvalidMessageHeaderException
     */
    private function getValidOrigin(Frame $frame): int
    {
        $origin = $frame->getOrigin();

        if ($origin !== 0 && !$this->options[self::OP_ALLOW_ORIGIN_VARIANCE]) {
            throw new InvalidMessageHeaderException("Message origin value expected to be 0, got {$origin}");
        }

        if ($origin < 0 || $origin > 3) {
            throw new InvalidMessageHeaderException("Message origin value {$origin} outside allowable range 0 - 3");
        }

        return $origin << 14;
    }

    private function getFlags(Frame $frame): int
    {
        return (((int)$frame->isTagged()) << 13) | (((int)$frame->isAddressable()) << 12);
    }

    /**
     * @param Frame $frame
     * @return int
     * @throws InvalidMessageHeaderException
     */
    private function getValidProtocolNumber(Frame $frame): int
    {
        $protocolNo = $frame->getProtocolNo();

        if ($protocolNo !== 1024 && !$this->options[self::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE]) {
            throw new InvalidMessageHeaderException("Protocol number value expected to be 1024, got {$protocolNo}");
        }

        if ($protocolNo < 0 || $protocolNo > 4095) {
            throw new InvalidMessageHeaderException("Message origin value {$protocolNo} outside allowable range 0 - 4095");
        }

        return $protocolNo;
    }

    /**
     * @param Frame $frame
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function encodeOriginFlagsAndProtocolNo(Frame $frame): string
    {
        $origin = $this->getValidOrigin($frame);
        $flags = $this->getFlags($frame);
        $protocolNo = $this->getValidProtocolNumber($frame);

        return \pack('v', $origin | $flags | $protocolNo);
    }

    /**
     * @param Frame $frame
     * @return string
     * @throws InvalidMessageHeaderException
     */
    private function encodeSource(Frame $frame): string
    {
        $source = $frame->getSource();

        if ($source < 0 || $source > 4294967295) {
            throw new InvalidMessageHeaderException("Message source value {$source} outside allowable range 0 - 4294967295");
        }

        return \pack('V', $source);
    }

    /**
     * @param Frame $frame
     * @return string
     * @throws InvalidMessageHeaderException
     */
    public function encodeFrame(Frame $frame): string
    {
        return $this->encodeMessageSize($frame)
            . $this->encodeOriginFlagsAndProtocolNo($frame)
            . $this->encodeSource($frame);
    }

    public function __construct(array $options = [])
    {
        $this->options = $options + [
            self::OP_ALLOW_ORIGIN_VARIANCE => false,
            self::OP_ALLOW_PROTOCOL_NUMBER_VARIANCE => false,
        ];
    }
}

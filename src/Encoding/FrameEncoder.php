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
     */
    private function encodeMessageSize(Frame $frame): string
    {
        return \pack('v', $frame->getSize());
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
     */
    private function encodeSource(Frame $frame): string
    {
        return \pack('V', $frame->getSource());
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

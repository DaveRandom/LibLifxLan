<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;
use function DaveRandom\LibLifxLan\validate_int_range;
use function DaveRandom\LibLifxLan\validate_uint16;
use function DaveRandom\LibLifxLan\validate_uint32;

final class Frame
{
    public const WIRE_SIZE = 8;

    private $size;
    private $origin;
    private $tagged;
    private $addressable;
    private $protocolNo;
    private $source;

    /**
     * @param int $size
     * @param int $origin
     * @param bool $tagged
     * @param bool $addressable
     * @param int $protocolNo
     * @param int $source
     * @throws InvalidValueException
     */
    public function __construct(int $size, int $origin, bool $tagged, bool $addressable, int $protocolNo, int $source)
    {
        $this->size = validate_uint16('Message size', $size);
        $this->origin = validate_int_range('Message origin', $origin, 0, 3);
        $this->tagged = $tagged;
        $this->addressable = $addressable;
        $this->protocolNo = validate_int_range('Protocol number', $protocolNo, 0, 0x0fff);
        $this->source = validate_uint32('Message source', $source);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getOrigin(): int
    {
        return $this->origin;
    }

    public function isTagged(): bool
    {
        return $this->tagged;
    }

    public function isAddressable(): bool
    {
        return $this->addressable;
    }

    public function getProtocolNo(): int
    {
        return $this->protocolNo;
    }

    public function getSource(): int
    {
        return $this->source;
    }
}

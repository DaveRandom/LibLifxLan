<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

final class Frame
{
    public const WIRE_SIZE = 8;

    private $size;
    private $origin;
    private $tagged;
    private $addressable;
    private $protocolNo;
    private $source;

    public function __construct(int $size, int $origin, bool $tagged, bool $addressable, int $protocolNo, int $source)
    {
        $this->size = $size;
        $this->origin = $origin;
        $this->tagged = $tagged;
        $this->addressable = $addressable;
        $this->protocolNo = $protocolNo;
        $this->source = $source;
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

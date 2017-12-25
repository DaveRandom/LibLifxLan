<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Header;

final class ProtocolHeader
{
    public const WIRE_SIZE = 12;

    private $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function getType(): int
    {
        return $this->type;
    }
}

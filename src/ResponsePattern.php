<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

final class ResponsePattern extends Enum
{
    public const REQUIRE_ACK = 0b01;
    public const REQUIRE_RESPONSE = 0b10;
}

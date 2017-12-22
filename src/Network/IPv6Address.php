<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Network;

final class IPv6Address extends IPAddress
{
    private $hextet1;
    private $hextet2;
    private $hextet3;
    private $hextet4;
    private $hextet5;
    private $hextet6;
    private $hextet7;
    private $hextet8;

    private $string;

    public static function createFromString(string $address): IPv6Address
    {
        if (false === ($binary = \inet_pton($address)) || \strlen($binary) !== 16) {
            throw new \InvalidArgumentException("Cannot parse '{$address}' as a valid IPv6 address");
        }

        return new IPv6Address(...\array_map(function($hextet) {
            return \unpack('n', $hextet)[1];
        } , \str_split($binary, 2)));
    }

    public function __construct(int $h1, int $h2, int $h3, int $h4, int $h5, int $h6, int $h7, int $h8)
    {
        if ($h1 < 0 || $h1 > 65535) {
            throw new \OutOfRangeException("Value '{$h1}' for hextet 1 outside range of allowable values 0 - 65535");
        }

        if ($h2 < 0 || $h2 > 65535) {
            throw new \OutOfRangeException("Value '{$h2}' for hextet 2 outside range of allowable values 0 - 65535");
        }

        if ($h3 < 0 || $h3 > 65535) {
            throw new \OutOfRangeException("Value '{$h3}' for hextet 3 outside range of allowable values 0 - 65535");
        }

        if ($h4 < 0 || $h4 > 65535) {
            throw new \OutOfRangeException("Value '{$h4}' for hextet 4 outside range of allowable values 0 - 65535");
        }

        if ($h5 < 0 || $h5 > 65535) {
            throw new \OutOfRangeException("Value '{$h5}' for hextet 5 outside range of allowable values 0 - 65535");
        }

        if ($h6 < 0 || $h6 > 65535) {
            throw new \OutOfRangeException("Value '{$h6}' for hextet 6 outside range of allowable values 0 - 65535");
        }

        if ($h7 < 0 || $h7 > 65535) {
            throw new \OutOfRangeException("Value '{$h7}' for hextet 7 outside range of allowable values 0 - 65535");
        }

        if ($h8 < 0 || $h8 > 65535) {
            throw new \OutOfRangeException("Value '{$h8}' for hextet 8 outside range of allowable values 0 - 65535");
        }

        $this->hextet1 = $h1;
        $this->hextet2 = $h2;
        $this->hextet3 = $h3;
        $this->hextet4 = $h4;
        $this->hextet5 = $h5;
        $this->hextet6 = $h6;
        $this->hextet7 = $h7;
        $this->hextet8 = $h8;
    }

    public function getHextet1(): int
    {
        return $this->hextet1;
    }

    public function getHextet2(): int
    {
        return $this->hextet2;
    }

    public function getHextet3(): int
    {
        return $this->hextet3;
    }

    public function getHextet4(): int
    {
        return $this->hextet4;
    }

    public function getHextet5(): int
    {
        return $this->hextet1;
    }

    public function getHextet6(): int
    {
        return $this->hextet2;
    }

    public function getHextet7(): int
    {
        return $this->hextet3;
    }

    public function getHextet8(): int
    {
        return $this->hextet4;
    }

    public function getStreamsUri(string $scheme, int $port): string
    {
        return \sprintf("%s://[%s]:%d", $scheme, (string)$this, $port);
    }

    public function getProtocolFamily(): int
    {
        return \STREAM_PF_INET6;
    }

    public function __toString(): string
    {
        return $this->string ?? $this->string = \inet_ntop(\pack(
            'n8',
            $this->hextet1, $this->hextet2, $this->hextet3, $this->hextet4,
            $this->hextet5, $this->hextet6, $this->hextet7, $this->hextet8
        ));
    }
}

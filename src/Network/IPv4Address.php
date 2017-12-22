<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Network;

final class IPv4Address extends IPAddress
{
    private $octet1;
    private $octet2;
    private $octet3;
    private $octet4;

    public static function createFromString(string $address): IPv4Address
    {
        if (false === ($binary = \inet_pton($address)) || \strlen($binary) !== 4) {
            throw new \InvalidArgumentException("Cannot parse '{$address}' as a valid IPv4 address");
        }

        return new IPv4Address(...\array_map('ord', \str_split($binary, 1)));
    }

    public function __construct(int $o1, int $o2, int $o3, int $o4)
    {
        if ($o1 < 0 || $o1 > 255) {
            throw new \OutOfRangeException("Value '{$o1}' for octet 1 outside range of allowable values 0 - 255");
        }

        if ($o2 < 0 || $o2 > 255) {
            throw new \OutOfRangeException("Value '{$o2}' for octet 2 outside range of allowable values 0 - 255");
        }

        if ($o3 < 0 || $o3 > 255) {
            throw new \OutOfRangeException("Value '{$o3}' for octet 3 outside range of allowable values 0 - 255");
        }

        if ($o4 < 0 || $o4 > 255) {
            throw new \OutOfRangeException("Value '{$o4}' for octet 4 outside range of allowable values 0 - 255");
        }

        $this->octet1 = $o1;
        $this->octet2 = $o2;
        $this->octet3 = $o3;
        $this->octet4 = $o4;
    }

    public function getOctet1(): int
    {
        return $this->octet1;
    }

    public function getOctet2(): int
    {
        return $this->octet2;
    }

    public function getOctet3(): int
    {
        return $this->octet3;
    }

    public function getOctet4(): int
    {
        return $this->octet4;
    }

    public function getStreamsUri(string $scheme, int $port): string
    {
        return \sprintf("%s://%s:%d", $scheme, (string)$this, $port);
    }

    public function getProtocolFamily(): int
    {
        return \STREAM_PF_INET;
    }

    public function __toString(): string
    {
        return \sprintf('%d.%d.%d.%d', $this->octet1, $this->octet2, $this->octet3, $this->octet4);
    }
}

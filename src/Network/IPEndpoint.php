<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Network;

final class IPEndpoint
{
    private $address;
    private $port;

    public static function parse(string $endpoint): IPEndpoint
    {
        if (2 === \count($parts = \explode(':', $endpoint))) {
            return new IPEndpoint(IPv4Address::parse($parts[0]), (int)$parts[1]);
        }

        if (\preg_match('/^\[([^\]]+)]:([0-9]+)/', $endpoint, $parts)) {
            return new IPEndpoint(IPv6Address::parse($parts[1]), (int)$parts[2]);
        }

        throw new \InvalidArgumentException("Cannot parse '{$endpoint}' as a valid IP endpoint");
    }

    public function __construct(IPAddress $address, int $port)
    {
        $this->address = $address;
        $this->port = $port;
    }

    public function getProtocolFamily(): int
    {
        return $this->address->getProtocolFamily();
    }

    public function equals(IPEndpoint $other): bool
    {
        return (string)$other === (string)$this;
    }

    public function __toString()
    {
        return ($this->address instanceof IPv6Address ? "[{$this->address}]" : $this->address) . ":{$this->port}";
    }
}

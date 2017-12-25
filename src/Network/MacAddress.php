<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Network;

final class MacAddress
{
    private $octet1;
    private $octet2;
    private $octet3;
    private $octet4;
    private $octet5;
    private $octet6;

    public static function createFromString(string $address): MacAddress
    {
        $stripped = \preg_replace('/[^0-9a-f]+/i', '', $address);

        if (\strlen($stripped) !== 12) {
            throw new \InvalidArgumentException("Cannot parse '{$address}' as a valid MAC address");
        }

        return new MacAddress(...\array_map('hexdec', \str_split($stripped, 2)));
    }

    public function __construct(int $o1, int $o2, int $o3, int $o4, int $o5, int $o6)
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

        if ($o5 < 0 || $o5 > 255) {
            throw new \OutOfRangeException("Value '{$o5}' for octet 5 outside range of allowable values 0 - 255");
        }

        if ($o6 < 0 || $o6 > 255) {
            throw new \OutOfRangeException("Value '{$o6}' for octet 6 outside range of allowable values 0 - 255");
        }

        $this->octet1 = $o1;
        $this->octet2 = $o2;
        $this->octet3 = $o3;
        $this->octet4 = $o4;
        $this->octet5 = $o5;
        $this->octet6 = $o6;
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

    public function getOctet5(): int
    {
        return $this->octet5;
    }

    public function getOctet6(): int
    {
        return $this->octet6;
    }

    public function __toString(): string
    {
        return \sprintf(
            '%02x:%02x:%02x:%02x:%02x:%02x',
            $this->octet1, $this->octet2, $this->octet3, $this->octet4, $this->octet5, $this->octet6
        );
    }

    public function __debugInfo(): array
    {
        return ['string' => $this->__toString()];
    }
}

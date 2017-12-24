<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LifxLan\Decoding\Exceptions\InvalidReadException;
use DaveRandom\LifxLan\Network\IPEndpoint;

final class PacketBuffer
{
    private $data;
    private $source;

    /**
     * PacketBuffer constructor.
     * @param string $data
     * @param IPEndpoint $source
     */
    public function __construct(string $data, IPEndpoint $source)
    {
        $this->data = $data;
        $this->source = $source;
    }

    public function getSource(): IPEndpoint
    {
        return $this->source;
    }

    /**
     * @param int $bytes
     * @param bool $peek
     * @return string
     * @throws InvalidReadException
     * @throws InsufficientDataException
     */
    public function read(int $bytes, bool $peek = false): string
    {
        if ($bytes < 1) {
            throw new InvalidReadException("Cannot read less than 1 byte from buffer");
        }

        if ($bytes > \strlen($this->data)) {
            throw new InsufficientDataException("Buffer does not have {$bytes} bytes available to read");
        }

        $result = \substr($this->data, 0, $bytes);

        if (!$peek) {
            $this->data = (string)\substr($this->data, $bytes);
        }

        return $result;
    }

    public function getLength(): int
    {
        return \strlen($this->data);
    }
}

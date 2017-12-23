<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Decoding\Exceptions\InsufficientDataException;
use DaveRandom\LifxLan\Decoding\Exceptions\InvalidReadException;

final class DataBuffer
{
    private $data;

    public function __construct(string $data = '')
    {
        $this->data = $data;
    }

    public function appendData(string $data = ''): void
    {
        $this->data .= $data;
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

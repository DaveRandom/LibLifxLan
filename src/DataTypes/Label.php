<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\DataTypes;

use DaveRandom\LibLifxLan\Exceptions\InvalidValueException;

final class Label
{
    private $value;

    /**
     * @param string $value
     * @throws InvalidValueException
     */
    private function setValue(string $value): void
    {
        if (\strlen($value) > 32) {
            throw new InvalidValueException("Label cannot be larger than 32 bytes, got " . \strlen($value) . " bytes");
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     * @throws InvalidValueException
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

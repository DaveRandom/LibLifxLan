<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages;

final class UnknownMessage implements Message
{
    private $typeId;
    private $data;

    public function __construct(int $typeId, string $data)
    {
        $this->typeId = $typeId;
        $this->data = $data;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getWireSize(): int
    {
        return \strlen($this->data);
    }
}

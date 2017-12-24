<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateLabel extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 25;

    private $label;

    public function __construct(string $label)
    {
        parent::__construct();

        $this->label = $label;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

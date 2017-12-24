<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Messages\Device\Responses;

use DaveRandom\LifxLan\DataTypes\Version;
use DaveRandom\LifxLan\Messages\ResponseMessage;

final class StateVersion extends ResponseMessage
{
    public const MESSAGE_TYPE_ID = 33;

    private $version;

    public function __construct(Version $version)
    {
        parent::__construct();

        $this->version = $version;
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function getTypeId(): int
    {
        return self::MESSAGE_TYPE_ID;
    }
}

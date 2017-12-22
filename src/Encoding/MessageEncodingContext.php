<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Encoding;

final class MessageEncodingContext
{
    /**
     * @var bool
     */
    public $isTagged = false;

    /**
     * @var bool
     */
    public $isAckRequired = false;

    /**
     * @var bool
     */
    public $isResponseRequired = true;
}

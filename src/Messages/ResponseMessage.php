<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: chris.wright
 * Date: 24/12/2017
 * Time: 18:11
 */

namespace DaveRandom\LifxLan\Messages;

abstract class ResponseMessage extends Message
{
    public function __construct(int $responsePattern = 0)
    {
        parent::__construct($responsePattern);
    }
}

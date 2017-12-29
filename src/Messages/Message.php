<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Messages;

interface Message
{
    function getTypeId(): int;
    function getWireSize(): int;
}

<?php declare(strict_types=1);

// This example does exactly what is described on the example packet page
// https://lan.developer.lifx.com/docs/building-a-lifx-packet
// The packet produced by this code should be identical to the example, except the "ack_required" bit will be set

use DaveRandom\LifxLan\Encoding\MessageEncoder;
use DaveRandom\LifxLan\Exceptions\InvalidMessageException;
use DaveRandom\LifxLan\HsbkColor;
use DaveRandom\LifxLan\Messages\Light\Instructions\SetColor;

require __DIR__ . '/../vendor/autoload.php';

$green = new HsbkColor(21845, 65535, 65535, 3500);
$message = new SetColor($green, 1024);

$encoder = new MessageEncoder([MessageEncoder::OP_SOURCE_ID => 0]);

try {
    $packet = $encoder->encodeSetColorMessage($message, null, 0);
} catch (InvalidMessageException $e) {
    exit((string)$e);
}

$hexDump = \implode(' ', \array_map(function($byte) {
    return \sprintf('%02X', \ord($byte));
}, \str_split($packet, 1)));

echo $hexDump . "\n";

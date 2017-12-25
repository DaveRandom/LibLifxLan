<?php declare(strict_types=1);

// This example does exactly what is described on the example packet page
// https://lan.developer.lifx.com/docs/building-a-lifx-packet
// The packet produced by this code should be identical to the example, except the "ack_required" bit will be set

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageException;
use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LibLifxLan\Network\IPEndpoint;
use function DaveRandom\LibLifxLan\Examples\udp_create_socket;

require __DIR__ . '/00_bootstrap.php';

$green = new HsbkColor(21845, 65535, 65535, 3500);
$message = new SetColor(new ColorTransition($green, 1024));

$encoder = new MessageEncoder([MessageEncoder::OP_SOURCE_ID => 0]);

try {
    $packet = $encoder->encodeSetColorMessage($message, null, 0);
} catch (InvalidMessageException $e) {
    exit((string)$e);
}

// uncomment this block to hex-dump the packet
/*
$hexDump = \implode(' ', \array_map(function($byte) {
    return \sprintf('%02X', \ord($byte));
}, \str_split($packet, 1)));

echo $hexDump . "\n";
*/

$socket = udp_create_socket(IPEndpoint::parse(LOCAL_ENDPOINT));
\stream_socket_sendto($socket, $packet, 0, (string)IPEndpoint::parse(BROADCAST_ENDPOINT));

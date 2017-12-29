<?php declare(strict_types=1);

// This example does exactly what is described on the example packet page
// https://lan.developer.lifx.com/docs/building-a-lifx-packet
// The packet produced by this code should be identical to the example

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageException;
use DaveRandom\LibLifxLan\Encoding\PacketEncoder;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LibLifxLan\Packet;
use DaveRandom\Network\IPEndpoint;
use function DaveRandom\LibLifxLan\Examples\udp_create_socket;

require __DIR__ . '/00_bootstrap.php';

$green = new HsbkColor(21845, 65535, 65535, 3500);
$message = new SetColor(new ColorTransition($green, 1024));
$packet = Packet::createFromMessage(
    $message,
    /* source */ 0,
    /* target */ null,
    /* seq */ 0,
    /* res/ack required */ 0
);

try {
    $data = (new PacketEncoder)->encodePacket($packet);
} catch (InvalidMessageException $e) {
    exit((string)$e);
}

// uncomment this block to hex-dump the packet
/*
$hexDump = \implode(' ', \array_map(function($byte) {
    return \sprintf('%02X', \ord($byte));
}, \str_split($data, 1)));

echo $hexDump . "\n";
*/

$socket = udp_create_socket(IPEndpoint::parse(LOCAL_ENDPOINT));
\stream_socket_sendto($socket, $data, 0, (string)IPEndpoint::parse(BROADCAST_ENDPOINT));

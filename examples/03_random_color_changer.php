<?php declare(strict_types=1);

// This example does exactly what is described on the example packet page
// https://lan.developer.lifx.com/docs/building-a-lifx-packet
// The packet produced by this code should be identical to the example, except the "ack_required" bit will be set

use DaveRandom\LibLifxLan\DataTypes\Light\ColorTransition;
use DaveRandom\LibLifxLan\Encoding\Exceptions\InvalidMessageException;
use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\DataTypes\Light\HsbkColor;
use DaveRandom\LibLifxLan\Messages\Device\Commands\SetPower;
use DaveRandom\LibLifxLan\Messages\Light\Commands\SetColor;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\Network\IPEndpoint;
use function DaveRandom\LibLifxLan\Examples\udp_create_socket;

require __DIR__ . '/00_bootstrap.php';

// Time in seconds that color transition should take
const TRANSITION_TIME = 1;

// Minimum time in seconds a single color should be used
const INTERVAL_MIN = 1;

// Maximum time in seconds a single color should be used
const INTERVAL_MAX = 10;

// Ranges of values for colors
const HUE_MIN = 0;
const HUE_MAX = 65535;
const SATURATION_MIN = 10000;
const SATURATION_MAX = 65535;
const BRIGHTNESS_MIN = 10000;
const BRIGHTNESS_MAX = 65535;
const TEMPERATURE_MIN = 3500;
const TEMPERATURE_MAX = 3500;

$encoder = new MessageEncoder([MessageEncoder::OP_SOURCE_ID => 0]);
$socket = udp_create_socket(IPEndpoint::parse(LOCAL_ENDPOINT));
$endpoint = IPEndpoint::parse(BROADCAST_ENDPOINT);

function broadcast_message(MessageEncoder $encoder, $socket, Message $message)
{
    static $endpoint;

    try {
        $packet = $encoder->encodeMessage($message, null, 0);
    } catch (InvalidMessageException $e) {
        exit((string)$e);
    }

    \stream_socket_sendto($socket, $packet, 0, (string)($endpoint ?? $endpoint = IPEndpoint::parse(BROADCAST_ENDPOINT)));
}

// Turn lights on
broadcast_message($encoder, $socket, new SetPower(65535));

while (true) {
    $hue = \rand(HUE_MIN, HUE_MAX);
    $saturation = \rand(SATURATION_MIN, SATURATION_MAX);
    $brightness = \rand(BRIGHTNESS_MIN, BRIGHTNESS_MAX);
    $temperature = \rand(TEMPERATURE_MIN, TEMPERATURE_MAX);

    $color = new HsbkColor($hue, $saturation, $brightness, $temperature);

    broadcast_message($encoder, $socket, new SetColor(new ColorTransition($color, (int)(TRANSITION_TIME * 1000))));

    \sleep(\rand(INTERVAL_MIN, INTERVAL_MAX));
}

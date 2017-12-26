<?php declare(strict_types=1);

use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\PacketDecoder;
use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\Messages\Device\Requests\GetService;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateService;
use DaveRandom\Network\IPEndpoint;
use function DaveRandom\LibLifxLan\Examples\udp_await_packets;
use function DaveRandom\LibLifxLan\Examples\udp_create_socket;

require __DIR__ . '/00_bootstrap.php';

$localEndpoint = IPEndpoint::parse(LOCAL_ENDPOINT);
$broadcastEndpoint = IPEndpoint::parse(BROADCAST_ENDPOINT);

$socket = udp_create_socket(IPEndpoint::parse(LOCAL_ENDPOINT));

$encoder = new MessageEncoder();
$decoder = new PacketDecoder();

try {
    $packet = $encoder->encodeMessage(new GetService, null, 0);
    \stream_socket_sendto($socket, $packet, 0, (string)$broadcastEndpoint);
} catch (\Throwable $e) {
    \fwrite(\STDERR, "Unhandled error while sending message: {$e}\n");
    exit(1);
}

foreach (udp_await_packets($socket, 1000) as [$buffer, $source]) {
    try {
        $packet = $decoder->decode($buffer);
    } catch (DecodingException $e) {
        echo "Decoding error: {$e->getMessage()}\n";
        continue;
    }

    $header = $packet->getHeader();
    $message = $packet->getMessage();

    if ($header->getFrame()->getSource() === MessageEncoder::DEFAULT_SOURCE_ID && $message instanceof StateService) {
        echo "{$source} announced service: {$message->getService()->getName()} on port {$message->getService()->getPort()}\n";
    }
}

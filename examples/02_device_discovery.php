<?php declare(strict_types=1);

use DaveRandom\LibLifxLan\Decoding\Exceptions\DecodingException;
use DaveRandom\LibLifxLan\Decoding\PacketDecoder;
use DaveRandom\LibLifxLan\Encoding\MessageEncoder;
use DaveRandom\LibLifxLan\Messages\Device\Requests\GetService;
use DaveRandom\LibLifxLan\Messages\Device\Responses\StateService;
use DaveRandom\LibLifxLan\Network\IPEndpoint;
use function DaveRandom\LibLifxLan\Examples\udp_await_packets;
use function DaveRandom\LibLifxLan\Examples\udp_create_socket;

require __DIR__ . '/00_bootstrap.php';

$localEndpoint = IPEndpoint::parse(LOCAL_ENDPOINT);
$broadcastEndpoint = IPEndpoint::parse(BROADCAST_ENDPOINT);

$socket = udp_create_socket(IPEndpoint::parse(LOCAL_ENDPOINT));

$encoder = new MessageEncoder();
$decoder = new PacketDecoder();

$packet = $encoder->encodeGetServiceMessage(new GetService);
\stream_socket_sendto($socket, $packet, 0, (string)$broadcastEndpoint);

foreach (udp_await_packets($socket, 1000) as $buffer) {
    try {
        $packet = $decoder->decode($buffer);
    } catch (DecodingException $e) {
        echo "Decoding error: {$e->getMessage()}\n";
        continue;
    }

    $header = $packet->getHeader();
    $message = $packet->getMessage();

    if ($header->getFrame()->getSource() === MessageEncoder::DEFAULT_SOURCE_ID && $message instanceof StateService) {
        echo "{$packet->getSource()} announced service: {$message->getService()->getName()} on port {$message->getService()->getPort()}\n";
    }
}

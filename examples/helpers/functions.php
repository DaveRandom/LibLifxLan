<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Examples;

use DaveRandom\LibLifxLan\Decoding\PacketBuffer;
use DaveRandom\LibLifxLan\Network\IPEndpoint;

/**
 * @param IPEndpoint $localEndpoint
 * @return resource
 */
function udp_create_socket(IPEndpoint $localEndpoint)
{
    $ctx = \stream_context_create(['socket' => ['so_broadcast' => true]]);

    if (!$socket = @\stream_socket_server('udp://' . $localEndpoint, $errNo, $errStr, \STREAM_SERVER_BIND, $ctx)) {
        throw new \RuntimeException("Failed to create local socket: {$errNo}: {$errStr}");
    }

    return $socket;
}

/**
 * @param int $timeoutMs
 * @return int[]
 */
function ms_to_secs_usecs(?int $timeoutMs): array
{
    return $timeoutMs !== null
        ? [(int)($timeoutMs / 1000), ($timeoutMs % 1000) * 1000]
        : [null, null];
}

/**
 * @param resource $socket
 * @param int $timeoutMs
 * @return PacketBuffer|null
 */
function udp_await_packet($socket, int $timeoutMs = null): ?PacketBuffer
{
    $r = [$socket];
    $w = $e = null;

    [$timeoutSecs, $timeoutUsecs] = ms_to_secs_usecs($timeoutMs);

    if (false === $count = \stream_select($r, $w, $e, $timeoutSecs, $timeoutUsecs ?? 0)) {
        throw new \RuntimeException("select() failed!");
    }

    if ($count === 0) {
        return null;
    }

    $data = \stream_socket_recvfrom($socket, 1024, 0, $address);

    return new PacketBuffer($data, IPEndpoint::parse($address));
}

/**
 * @param resource $socket
 * @param int $timeoutMs
 * @return PacketBuffer[]
 */
function udp_await_packets($socket, int $timeoutMs): array
{
    $packets = [];

    $start = \microtime(true);
    $remaining = $timeoutMs;

    do {
        if (null === $buffer = udp_await_packet($socket, $remaining)) {
            break;
        }

        $packets[] = $buffer;
        $remaining = (int)($timeoutMs - ((\microtime(true) - $start) * 1000));
    } while ($remaining > 0);

    return $packets;
}

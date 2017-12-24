<?php declare(strict_types=1);

// The local endpoint to bind to - 0.0.0.0 will work for pretty much any situation.
// Port 56700 should be used if at all possible for best compat with older LIFX devices.
const LOCAL_ENDPOINT = '0.0.0.0:56700';

// The address to which broadcast traffic will be sent - 255.255.255.255 will work for pretty much any situation
// The port should always be 56700
const BROADCAST_ENDPOINT = '255.255.255.255:56700';

<?php declare(strict_types=1);

namespace DaveRandom\LifxLan\Decoding;

use DaveRandom\LifxLan\Decoding\Exceptions\UnknownMessageTypeException;
use DaveRandom\LifxLan\Messages\Device\Responses\Acknowledgement;
use DaveRandom\LifxLan\Messages\Device\Responses\EchoResponse;
use DaveRandom\LifxLan\Messages\Device\Responses\StateGroup;
use DaveRandom\LifxLan\Messages\Device\Responses\StateHostFirmware;
use DaveRandom\LifxLan\Messages\Device\Responses\StateHostInfo;
use DaveRandom\LifxLan\Messages\Device\Responses\StateLabel;
use DaveRandom\LifxLan\Messages\Device\Responses\StateLocation;
use DaveRandom\LifxLan\Messages\Device\Responses\StatePower as StateDevicePower;
use DaveRandom\LifxLan\Messages\Device\Responses\StateService;
use DaveRandom\LifxLan\Messages\Device\Responses\StateVersion;
use DaveRandom\LifxLan\Messages\Device\Responses\StateWifiFirmware;
use DaveRandom\LifxLan\Messages\Device\Responses\StateWifiInfo;
use DaveRandom\LifxLan\Messages\Light\Responses\State;
use DaveRandom\LifxLan\Messages\Light\Responses\StateInfrared;
use DaveRandom\LifxLan\Messages\Light\Responses\StatePower as StateLightPower;
use DaveRandom\LifxLan\Messages\Message;

final class MessageDecoder
{
    /**
     * @var callable[]
     */
    private const DECODER_ROUTINES = [
        // Device messages
        Acknowledgement::MESSAGE_TYPE_ID => ['self', 'decodeAcknowledgement'],
        EchoResponse::MESSAGE_TYPE_ID => [], // todo
        StateGroup::MESSAGE_TYPE_ID => [], // todo
        StateHostFirmware::MESSAGE_TYPE_ID => [], // todo
        StateHostInfo::MESSAGE_TYPE_ID => [], // todo
        StateLabel::MESSAGE_TYPE_ID => [], // todo
        StateLocation::MESSAGE_TYPE_ID => [], // todo
        StateDevicePower::MESSAGE_TYPE_ID => [], // todo
        StateService::MESSAGE_TYPE_ID => [], // todo
        StateVersion::MESSAGE_TYPE_ID => [], // todo
        StateWifiFirmware::MESSAGE_TYPE_ID => [], // todo
        StateWifiInfo::MESSAGE_TYPE_ID => [], // todo

        // Light messages
        State::MESSAGE_TYPE_ID => [], // todo
        StateInfrared::MESSAGE_TYPE_ID => [], // todo
        StateLightPower::MESSAGE_TYPE_ID => [], // todo
    ];

    /**
     * @return Acknowledgement
     * @uses decodeAcknowledgement
     */
    private static function decodeAcknowledgement(): Acknowledgement
    {
        return new Acknowledgement();
    }

    /**
     * @param int $type
     * @param string $data
     * @return Message
     * @throws UnknownMessageTypeException
     */
    public function decodeMessage(int $type, string $data): Message
    {
        if (!\array_key_exists($type, self::DECODER_ROUTINES)) {
            throw new UnknownMessageTypeException("Unknown message type: {$type}");
        }

        return (self::DECODER_ROUTINES[$type])($data);
    }
}

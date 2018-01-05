<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan\Encoding;

use DaveRandom\LibLifxLan\DataTypes as DeviceDataTypes;
use DaveRandom\LibLifxLan\DataTypes\Light as LightDataTypes;
use DaveRandom\LibLifxLan\Messages\Device\Commands as DeviceCommands;
use DaveRandom\LibLifxLan\Messages\Device\Requests as DeviceRequests;
use DaveRandom\LibLifxLan\Messages\Device\Responses as DeviceResponses;
use DaveRandom\LibLifxLan\Messages\Light\Commands as LightCommands;
use DaveRandom\LibLifxLan\Messages\Light\Responses as LightResponses;
use DaveRandom\LibLifxLan\Messages\Message;
use DaveRandom\LibLifxLan\Messages\UnknownMessage;
use const DaveRandom\LibLifxLan\FLOAT32_CODE;
use function DaveRandom\LibLifxLan\datetimeinterface_to_nanotime;
use function DaveRandom\LibLifxLan\int16_to_uint16;

final class MessageEncoder
{
    /**
     * @uses encodeUnknownMessage
     * @uses encodeEchoRequest
     * @uses encodeEchoResponse
     * @uses encodeSetGroup
     * @uses encodeStateGroup
     * @uses encodeStateHostFirmware
     * @uses encodeStateHostInfo
     * @uses encodeStateInfo
     * @uses encodeSetLabel
     * @uses encodeStateLabel
     * @uses encodeSetLocation
     * @uses encodeStateLocation
     * @uses encodeSetDevicePower
     * @uses encodeStateDevicePower
     * @uses encodeStateService
     * @uses encodeStateVersion
     * @uses encodeStateWifiFirmware
     * @uses encodeStateWifiInfo
     * @uses encodeSetColor
     * @uses encodeSetWaveform
     * @uses encodeSetWaveformOptional
     * @uses encodeState
     * @uses encodeSetInfrared
     * @uses encodeStateInfrared
     * @uses encodeSetLightPower
     * @uses encodeStateLightPower
     */
    private const ENCODING_ROUTINES = [
        UnknownMessage::class => 'UnknownMessage',

        // Device command messages
        DeviceCommands\SetGroup::class => 'SetGroup',
        DeviceCommands\SetLabel::class => 'SetLabel',
        DeviceCommands\SetLocation::class => 'SetLocation',
        DeviceCommands\SetPower::class => 'SetDevicePower',

        // Device request messages
        DeviceRequests\EchoRequest::class => 'EchoRequest',

        // Device response messages
        DeviceResponses\EchoResponse::class => 'EchoResponse',
        DeviceResponses\StateGroup::class => 'StateGroup',
        DeviceResponses\StateHostFirmware::class => 'StateHostFirmware',
        DeviceResponses\StateHostInfo::class => 'StateHostInfo',
        DeviceResponses\StateInfo::class => 'StateInfo',
        DeviceResponses\StateLabel::class => 'StateLabel',
        DeviceResponses\StateLocation::class => 'StateLocation',
        DeviceResponses\StatePower::class => 'StateDevicePower',
        DeviceResponses\StateService::class => 'StateService',
        DeviceResponses\StateVersion::class => 'StateVersion',
        DeviceResponses\StateWifiFirmware::class => 'StateWifiFirmware',
        DeviceResponses\StateWifiInfo::class => 'StateWifiInfo',

        // Light command messages
        LightCommands\SetColor::class => 'SetColor',
        LightCommands\SetInfrared::class => 'SetInfrared',
        LightCommands\SetPower::class => 'SetLightPower',
        LightCommands\SetWaveform::class => 'SetWaveform',
        LightCommands\SetWaveformOptional::class => 'SetWaveformOptional',

        // Light response messages
        LightResponses\State::class => 'State',
        LightResponses\StateInfrared::class => 'StateInfrared',
        LightResponses\StatePower::class => 'StateLightPower',
    ];

    private function encodeHsbkColor(LightDataTypes\HsbkColor $color): string
    {
        return \pack('v4', $color->getHue(), $color->getSaturation(), $color->getBrightness(), $color->getTemperature());
    }

    private function encodeLocation(DeviceDataTypes\Location $location): string
    {
        return \pack(
            'a16a32P',
            $location->getGuid()->getBytes(),
            $location->getLabel()->getValue(),
            datetimeinterface_to_nanotime($location->getUpdatedAt())
        );
    }

    private function encodeGroup(DeviceDataTypes\Group $group): string
    {
        return \pack(
            'a16a32P',
            $group->getGuid()->getBytes(),
            $group->getLabel()->getValue(),
            datetimeinterface_to_nanotime($group->getUpdatedAt())
        );
    }

    private function encodeFirmware(DeviceDataTypes\Firmware $firmware)
    {
        return \pack(
            'PPV',
            datetimeinterface_to_nanotime($firmware->getBuild()),
            0, // reserved
            $firmware->getVersion()
        );
    }

    private function encodeNetworkInfo(DeviceDataTypes\NetworkInfo $info): string
    {
        return \pack(
            FLOAT32_CODE . 'VVv',
            $info->getSignal(),
            $info->getTx(),
            $info->getRx(),
            0 // reserved
        );
    }

    private function encodeUnknownMessage(UnknownMessage $message): string
    {
        return $message->getData();
    }

    private function encodeStateVersion(DeviceResponses\StateVersion $message): string
    {
        $version = $message->getVersion();

        return \pack('VVV', $version->getVendor(), $version->getProduct(), $version->getVersion());
    }

    private function encodeStateService(DeviceResponses\StateService $message): string
    {
        $service = $message->getService();

        return \pack('CV', $service->getTypeId(), $service->getPort());
    }

    private function encodeStateInfo(DeviceResponses\StateInfo $message): string
    {
        $info = $message->getInfo();

        return \pack('PPP', datetimeinterface_to_nanotime($info->getTime()), $info->getUptime(), $info->getDowntime());
    }

    private function encodeStateHostFirmware(DeviceResponses\StateHostFirmware $message): string
    {
        return $this->encodeFirmware($message->getHostFirmware());
    }

    private function encodeStateHostInfo(DeviceResponses\StateHostInfo $message): string
    {
        return $this->encodeNetworkInfo($message->getHostInfo());
    }

    private function encodeStateWifiFirmware(DeviceResponses\StateWifiFirmware $message): string
    {
        return $this->encodeFirmware($message->getWifiFirmware());
    }

    private function encodeStateWifiInfo(DeviceResponses\StateWifiInfo $message): string
    {
        return $this->encodeNetworkInfo($message->getWifiInfo());
    }

    private function encodeSetGroup(DeviceCommands\SetGroup $message): string
    {
        return $this->encodeGroup($message->getGroup());
    }

    private function encodeStateGroup(DeviceResponses\StateGroup $message): string
    {
        return $this->encodeGroup($message->getGroup());
    }

    private function encodeSetLabel(DeviceCommands\SetLabel $message): string
    {
        return \pack('a32', $message->getLabel()->getValue());
    }

    private function encodeStateLabel(DeviceResponses\StateLabel $message): string
    {
        return \pack('a32', $message->getLabel()->getValue());
    }

    private function encodeSetLocation(DeviceCommands\SetLocation $message): string
    {
        return $this->encodeLocation($message->getLocation());
    }

    private function encodeStateLocation(DeviceResponses\StateLocation $message): string
    {
        return $this->encodeLocation($message->getLocation());
    }

    private function encodeSetDevicePower(DeviceCommands\SetPower $message): string
    {
        return \pack('v', $message->getLevel());
    }

    private function encodeStateDevicePower(DeviceResponses\StatePower $message): string
    {
        return \pack('v', $message->getLevel());
    }

    private function encodeEchoRequest(DeviceRequests\EchoRequest $message): string
    {
        return $message->getPayload();
    }

    private function encodeEchoResponse(DeviceResponses\EchoResponse $message): string
    {
        return $message->getPayload();
    }

    private function encodeSetColor(LightCommands\SetColor $message): string
    {
        $transition = $message->getColorTransition();

        return "\x00" . $this->encodeHsbkColor($transition->getColor()) . \pack('V', $transition->getDuration());
    }

    private function encodeSetInfrared(LightCommands\SetInfrared $message): string
    {
        return \pack('v', $message->getBrightness());
    }

    private function encodeStateInfrared(LightResponses\StateInfrared $message): string
    {
        return \pack('v', $message->getBrightness());
    }

    private function encodeSetLightPower(LightCommands\SetPower $message): string
    {
        $transition = $message->getPowerTransition();

        return \pack('vV', $transition->getLevel(), $transition->getDuration());
    }

    private function encodeStateLightPower(LightResponses\StatePower $message): string
    {
        return \pack('v', $message->getLevel());
    }

    private function encodeState(LightResponses\State $message): string
    {
        $state = $message->getState();

        return $this->encodeHsbkColor($state->getColor()) . \pack(
            'v2a32P',
            0, // reserved
            $state->getPower(),
            $state->getLabel()->getValue(),
            0  // reserved
        );
    }

    private function encodeSetWaveform(LightCommands\SetWaveform $message): string
    {
        $effect = $message->getEffect();
        $skew = int16_to_uint16($effect->getSkewRatio());

        return "\x00" . \chr((int)$effect->isTransient())
            . $this->encodeHsbkColor($effect->getColor())
            . \pack('V' . FLOAT32_CODE . 'vC', $effect->getPeriod(), $effect->getCycles(), $skew, $effect->getWaveform())
        ;
    }

    private function encodeSetWaveformOptional(LightCommands\SetWaveformOptional $message): string
    {
        $effect = $message->getEffect();
        $skew = int16_to_uint16($effect->getSkewRatio());

        $options = $effect->getOptions();
        $optionData = \pack(
            'C4',
            (int)(bool)($options & LightDataTypes\Effect::SET_HUE),
            (int)(bool)($options & LightDataTypes\Effect::SET_SATURATION),
            (int)(bool)($options & LightDataTypes\Effect::SET_BRIGHTNESS),
            (int)(bool)($options & LightDataTypes\Effect::SET_TEMPERATURE)
        );

        return "\x00" . \chr((int)$effect->isTransient())
            . $this->encodeHsbkColor($effect->getColor())
            . \pack('V' . FLOAT32_CODE . 'vC', $effect->getPeriod(), $effect->getCycles(), $skew, $effect->getWaveform())
            . $optionData
        ;
    }

    public function encodeMessage(Message $message): string
    {
        return \array_key_exists($class = \get_class($message), self::ENCODING_ROUTINES)
            ? $this->{'encode' . self::ENCODING_ROUTINES[$class]}($message)
            : '';
    }
}

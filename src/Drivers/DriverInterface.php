<?php

namespace Nksquare\Sms\Drivers;

use Nksquare\Sms\Message;

interface DriverInterface {
    /**
     * @param $message \Nksquare\Sms\Message
     */
    public function send(Message $message);

    /**
     * @param $messages array
     * @param $sender string|null
     */
    public function bulk(array $messages,$sender=null);
}
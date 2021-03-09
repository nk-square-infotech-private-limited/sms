<?php

namespace Nksquare\Sms;

class Message
{
    /**
     * @var string
     */
    protected $recipient;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $sender;

    /**
     * @var bool
     */
    public $unicode = false;

    /**
     * @param $recipient string
     * @return self
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param $message string
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param $sender string
     * @return self
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }
}
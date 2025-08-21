<?php

namespace Nksquare\Sms;

class Message
{
    /**
     * @var string
     */
    protected ?string $recipient = null;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var string
     */
    protected ?string $sender = null;

    /**
     * @var bool
     */
    public bool $unicode = false;

    /**
     * @var ?string
     */
    public ?string $templateId = null;

    /**
     * @param $recipient string
     * @return self
     */
    public function setRecipient(string $recipient) : static
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param $message string
     * @return self
     */
    public function setMessage(string $message) : static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param $sender string
     * @return self
     */
    public function setSender(string $sender) : static
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient() : ?string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getSender() : ?string
    {
        return $this->sender;
    }

    /**
     * @param $templateId string
     * @return self
     */
    public function setTemplateId(string $templateId) : static
    {
        $this->templateId = $templateId;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getTemplateId() : ?string
    {
        return $this->templateId;
    }
}
<?php

namespace Nksquare\Sms;

use Nksquare\Sms\Drivers\DriverInterface;

class Client
{
    /**
     * @var \Nksquare\Sms\Drivers\DriverInterface
     */
    protected DriverInterface $driver;
    
    /**
     * @param $driver \Nksquare\Sms\Drivers\DriverInterface
     */
    function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

       /** 
     * @param $message \Nksquare\Sms\Message
     */
    public function send(Message $message) : void
    {
        $this->driver->send($message);
    }

    /** 
     * @param $messages array
     * @param $sender string|null
     */
    public function bulk(array $messages) : void
    {
        $this->driver->bulk($messages);
    }

    /** 
     * @return Nksquare\Sms\Drivers\DriverInterface
     */
    public function getDriver() : DriverInterface
    {
        return $this->driver;
    }
}
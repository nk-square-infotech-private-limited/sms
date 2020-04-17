<?php

namespace Nksquare\Sms;

use Nksquare\Sms\Drivers\DriverInterface;

class Client
{
    /**
     * @var \Nksquare\Sms\Drivers\DriverInterface
     */
    protected $driver;
    
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
    public function send(Message $message)
    {
        $this->driver->send($message);
    }

    /** 
     * @param $messages array
     * @param $sender string|null
     */
    public function bulk(array $messages)
    {
        $this->driver->bulk($messages);
    }

    /** 
     * @return Nksquare\Sms\Drivers\DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }
}
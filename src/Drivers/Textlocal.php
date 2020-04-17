<?php

namespace Nksquare\Sms\Drivers;

use Nksquare\Sms\Exceptions\InvalidConfigException;
use Nksquare\Sms\Message;

class Textlocal implements DriverInterface
{
    /**
     * @var string
     */
    protected $endpoint = 'https://api.textlocal.in';

    /**
     * @var boolean
     */
    protected $test = false;

    /** 
     * @param $config array
     */
    function __construct($config)
    {
        if(empty($config['apikey']))
        {
            throw new InvalidConfigException('api key not set for textlocal driver');
        }

        $this->apikey = $config['apikey'];

        if(isset($config['endpoint']))
        {
            $this->endpoint = $config['endpoint'];
        }

        if(isset($config['sender']))
        {
            $this->sender = $config['sender'];
        }

        if(!empty($config['test']))
        {
            $this->test = true;
        }
    }

    /** 
     * @param $message \Nksquare\Sms\Message
     */
    public function send(Message $message)
    {
        $data = [
            'apikey' => $this->apikey, 
            'sender' => $message->getSender() ?? $this->sender, 
            'numbers' => $message->getRecipient(), 
            'message' => rawurlencode($message->getMessage()),
            'test' => $this->test,
        ];

        $ch = curl_init($this->endpoint.'/send/');

        curl_setopt_array($ch,[
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);

        curl_close($ch);
    }

    /** 
     * @param $messages array
     * @param $sender string|null
     */
    public function bulk(array $messages,$sender=null)
    {
        $bulk['sender'] = $sender ?? $this->sender;

        foreach ($messages as $message) 
        {
            $bulk['messages'][] = [
                'number' => $message->getRecipient(),
                'text' => $message->getMessage(),
            ];
        }

        $data = [
            'apikey' => $this->apikey,
            'data' => json_encode($bulk), 
            'test' => $this->test,
        ];

        $ch = curl_init($this->endpoint.'/bulk_json');

        curl_setopt_array($ch,[
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);

        curl_close($ch);
    }
}
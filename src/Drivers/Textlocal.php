<?php

namespace Nksquare\Sms\Drivers;

use Nksquare\Sms\Exceptions\InvalidConfigException;
use Nksquare\Sms\Exceptions\SmsException;
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
     * @throws \Nksquare\Sms\Exceptions\SmsException
     */
    public function send(Message $message)
    {
        $data = [
            'apikey' => $this->apikey, 
            'sender' => $message->getSender() ?? $this->sender, 
            'numbers' => $message->getRecipient(), 
            'message' => rawurlencode($message->getMessage()),
            'test' => $this->test,
            'unicode' => $message->unicode,
        ];

        $ch = curl_init($this->endpoint.'/send/');

        curl_setopt_array($ch,[
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = json_decode(curl_exec($ch));

        if($response==null)
        {
            throw new SmsException('Connection error');
        }

        if(strtolower($response->status)=='failure')
        {
            throw new SmsException($response->errors[0]->message);
        }

        curl_close($ch);
    }

    /** 
     * @param $messages array
     * @param $sender string|null
     * @throws \Nksquare\Sms\Exceptions\SmsException
     */
    public function bulk(array $messages,$sender=null)
    {
        $bulk['sender'] = $sender ?? $this->sender;

        $bulk['test'] = $this->test;

        foreach ($messages as $message) 
        {
            if(empty($message->getRecipient()))
            {
                throw new SmsException('No recipient specified');
            }
            $bulk['messages'][] = [
                'number' => $message->getRecipient(),
                'text' => $message->getMessage(),
            ];
        }

        $data = [
            'apikey' => $this->apikey,
            'data' => json_encode($bulk)
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
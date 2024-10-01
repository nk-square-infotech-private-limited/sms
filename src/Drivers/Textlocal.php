<?php

namespace Nksquare\Sms\Drivers;

use GuzzleHttp\Client;
use Nksquare\Sms\Message;
use Nksquare\Sms\Exceptions\SmsException;
use Nksquare\Sms\Exceptions\InvalidConfigException;

class Textlocal implements DriverInterface
{
    protected string $endpoint = 'https://api.textlocal.in';
    
    protected bool $test = false;
    
    protected string $apikey;

    protected string $sender;

    /** 
     * @param $config array
     */
    function __construct(array $config)
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
    public function send(Message $message) : void
    {
        $data = [
            'apikey' => $this->apikey, 
            'sender' => $message->getSender() ?? $this->sender, 
            'numbers' => $message->getRecipient(), 
            'message' => rawurlencode($message->getMessage()),
            'test' => $this->test,
            'unicode' => $message->unicode,
        ];

        $client = new Client();


        $response = $client->post($this->endpoint . '/send/', [
            'form_params' => $data
        ]);

        $response = json_decode($response->getBody());

        $this->parseResponseAndThrowException($response);
    }

    /** 
     * @param $messages array
     * @param $sender string|null
     * @throws \Nksquare\Sms\Exceptions\SmsException
     */
    public function bulk(array $messages,?string $sender=null) : void
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

        $client = new Client();

        $response = $client->post($this->endpoint . '/bulk_json', [
            'form_params' => $data
        ]);

        $response = json_decode($response->getBody());

        $this->parseResponseAndThrowException($response);
    }

    protected function parseResponseAndThrowException($response)
    {
        if($response==null)
        {
            throw new SmsException('Connection error');
        }

        if(strtolower($response->status)=='failure')
        {
            throw new SmsException($response->errors[0]->message);
        }
    }
}
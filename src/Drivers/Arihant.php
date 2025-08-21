<?php

namespace Nksquare\Sms\Drivers;

use GuzzleHttp\Client;
use Nksquare\Sms\Message;
use Nksquare\Sms\Exceptions\SmsException;
use GuzzleHttp\Exception\RequestException;

class Arihant implements DriverInterface
{
    protected $username;
    protected $password;
    protected $sender;
    protected $dltPe;
    protected $client;

    const ENDPOINT = 'https://control.arihantglobal.in/fe/api/v1/send';

    public function __construct(array $config)
    {
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->sender   = $config['sender'];
        $this->dltPe    = $config['dlt_pe'];

        $this->client = new Client([
            'base_uri' => self::ENDPOINT,
            'timeout'  => 10.0,
        ]);
    }
    
    public function send(Message $message) : void
    {
        $params = [
            'query' => [
                'username' => $this->username,
                'password' => $this->password,
                'dltPrincipalEntityId' => $this->dltPe,
                'from' => $this->sender,
                'to' => $message->getRecipient(),
                'unicode' => true,
                'text' => $message->getMessage(),
                'dltContentId' => $message->getTemplateId(),
            ]
        ];

        try {
            $response = $this->client->request('GET', '', $params);
            $status   = $response->getStatusCode();
            if ($status !== 200) {
                throw new SmsException('Arihant API Error: ' . $response->getBody()->getContents());
            }
            $body = json_decode($response->getBody()->getContents(), true);
            if (!isset($body['statusCode']) || $body['statusCode'] !== 200) {
                throw new SmsException('Arihant API Error: ' . json_encode($body));
            }
        } catch (RequestException $e) {
            throw new SmsException('HTTP Request Failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function bulk(array $messages,?string $sender=null) : void
    {
        throw new SmsException('Bulk sms not support for arihant driver');
    }
}

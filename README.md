# SMS
Sms library with support for multiple sms providers

## Installation
Run composer 
```
composer require nksquare\sms
```
## Usage
```php
use Nksquare\Sms\Drivers\Textlocal;
use Nksquare\Sms\Client;
use Nksquare\Sms\Message;

$config = [
    'apikey' => 'your_api_key',
    'sender' => 'SENDER',
];
$driver = new Textlocal($config);
$client = new Client($driver);

$message = new Message();
$message->setMessage('Hello world');
$message->setRecipient('1234567890');
$client->send($message);
```

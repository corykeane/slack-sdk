slack-sdk
=========

[![Latest Stable Version](https://poser.pugx.org/threadmeup/slack-sdk/v/stable.svg)](https://packagist.org/packages/threadmeup/slack-sdk) [![Total Downloads](https://poser.pugx.org/threadmeup/slack-sdk/downloads.svg)](https://packagist.org/packages/threadmeup/slack-sdk) [![Latest Unstable Version](https://poser.pugx.org/threadmeup/slack-sdk/v/unstable.svg)](https://packagist.org/packages/threadmeup/slack-sdk) [![License](https://poser.pugx.org/threadmeup/slack-sdk/license.svg)](https://packagist.org/packages/threadmeup/slack-sdk)

Simple SDK for interacting with Slack.com via the API and webhooks.

## Install

You can install slack-sdk by using `composer require threadmeup/slack-sdk dev-master`.

## Configure

```php
include 'vendor/autoload.php';

use ThreadMeUp\Slack\Client;

$config = [
    'token' => 'USER-API-TOKEN',
    'team' => 'YOUR-TEAM',
    'username' => 'BOT-NAME',
    'icon' => 'ICON' // Auto detects if it's an icon_url or icon_emoji
];

$slack = new Client($config);
```
## Examples

What we're doing here is sending the message `Hello World!` to the `#general` channel
```php
$chat = $slack->chat('#general');
$chat->send('Hello World!');
```

We can also list all users in the team
```php
$users = $slack->users();
foreach ($users as $user)
{
    echo ($user->isAdmin() ? 'Admin' : 'User').': '.$user->name().' <'.$user->email().'>'.PHP_EOL;
}
```

Or even listen to outgoing webhooks from Slack themselves.
```php
$incoming = $slack->listen();
if ($incoming)
{
    switch($incoming->text())
    {
        case "What time is it?":
            $incoming->respond("It is currently ".date('g:m A T'));
        break;
        default:
            $incoming->respond("I don't understand what you're asking.");
        break;
    }
}
```

For testing reasons you can pass `Client::listen()` an array of the payload to simulate which will then ignore any `$_POST` values and use the `$payload` you supplied instead.
```php
$payload = [
    'token' => 'YNgeXsCXyWgAMfCvjc7NUUpz',
    'team_id' => 'T0001',
    'channel_id' => 'C2147483705',
    'channel_name' => 'test',
    'timestamp' => '1355517523.000005',
    'user_id' => 'U2147483697',
    'user_name' => 'Steve',
    'text' => 'googlebot: What is the air-speed velocity of an unladen swallow?'
];
$incoming = $slack->listen($payload);
```

slack-sdk
=========

Simple SDK for interacting with Slack.com via the webhooks system.

Currently all you can do is send messages to a channel.

## Usage ##

```php
include 'vendor/autoload.php';

use Killswitch\Slack\Client as Slack;

$slack = new Slack('yourteam', 'webhook-key');
$slack->setConfig(array(
    'channel' => '#general',
    'username' => 'Dave',
    'icon_url' => null,
    'icon_emoji' => ':godmode:'
));
$slack->say('#general', 'I am Dave!');
```

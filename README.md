SpaceTransportBundle
====================

A bundle symfony for Jetbrains Space Transport in symfony notifier.


Installation
------------

Open a command console, enter your project directory and execute:

```bash 
composer require proglab/space-client-bundle
```

f you're not using symfony/flex, enable the bundle by adding it to the list of registered bundles in the config/bundles.php file of your project:

```php
return [
    // ...
    Proglab\SpaceClientBundle\SpaceClientBundle::class => ['all' => true],
];
```

Copy ```src/Resources/config/space-transport.yaml``` to your ```config/packages/space-transport.yaml``` and update the space url

And finally you must add this to your .env file :

```dotenv
###> proglab/space-transport-bundle ###
SPACE_DSN=space://{{token}}@default?channel={{default_channel}}
###< proglab/space-transport-bundle ###
```

Usage
-----

The default space Channel is defined in your .env file.

```php
use Proglab\SftpClientBundle\Service\SftpClient;


$notification = new SpaceNotification('Welcome Aboard', ['chat/space']);
$notification->setChannel('Pimcore'); // you can override the default channel if necessary
$notifier->send($notification);
```


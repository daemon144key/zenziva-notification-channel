# Zenziva notifications channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/daemon144key/zenziva-notification-channel.svg?style=flat-square)](https://packagist.org/packages/daemon144key/zenziva-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/daemon144key/zenziva-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/daemon144key/zenziva-notification-channel)
[![StyleCI](https://styleci.io/repos/65714964/shield)](https://styleci.io/repos/65714964)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/853ee111-4bcf-4955-842c-dcd666da77a1.svg?style=flat-square)](https://insight.sensiolabs.com/projects/853ee111-4bcf-4955-842c-dcd666da77a1)
[![Quality Score](https://img.shields.io/scrutinizer/g/daemon144key/zenziva-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/daemon144key/zenziva-notification-channel)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/daemon144key/zenziva-notification-channel/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/daemon144key/zenziva-notification-channel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/daemon144key/zenziva-notification-channel.svg?style=flat-square)](https://packagist.org/packages/daemon144key/zenziva-notification-channel)


This package makes it easy to send SMS notifications using [zenziva.id](http://www.zenziva.id/) with Laravel 5.3 onward.

## Contents

- [Installation](#installation)
    - [Setting up the Clickatell service](#setting-up-the-zenziva-notification-channel-service)
- [Usage](#usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require daemon144key/zenziva-notification-channel
```

You must install the service provider and add to your `config/app.php` (skip for Laravel 5.5 onward):
```php
// config/app.php
'providers' => [
    ...
    TuxDaemon\ZenzivaNotificationChannel\ZenzicaServiceProvider::class,
],
```

### Setting up the zenziva-notification-channel service

Add your Zenziva account username and password to your `config/services.php`:

```php
// config/services.php
...
'zenziva' => [
    'username' => env('ZENZIVA_SMS_CLIENT_USERNAME', ''),
    'password' => env('ZENZIVA_SMS_CLIENT_PASSWORD', ''),
    'masking' => env('ZENZIVA_SMS_CLIENT_MASKING', false)
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use TuxDaemon\ZenzivaNotificationChannel\ZenzivaMessage;
use TuxDaemon\ZenzivaNotificationChannel\ZenzivaChannel;

class OrderCreated extends Notification
{
    public function via($notifiable)
    {
        return [ZenzivaChannel::class];
    }

    public function toZenziva($notifiable)
    {
        return (new ZenzivaMessage())
            ->content("Your order had created!");
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

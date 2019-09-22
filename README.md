# Zenziva notifications channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/daemon144key/zenziva-notification-channel.svg?style=flat-square)](https://packagist.org/packages/daemon144key/zenziva-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/daemon144key/zenziva-notification-channel.svg?branch=master)](https://travis-ci.org/daemon144key/zenziva-notification-channel)
[![StyleCI](https://github.styleci.io/repos/142868777/shield?branch=master)](https://github.styleci.io/repos/142868777)
[![Total Downloads](https://img.shields.io/packagist/dt/daemon144key/zenziva-notification-channel.svg?style=flat-square)](https://packagist.org/packages/daemon144key/zenziva-notification-channel)


This package makes it easy to send SMS notifications using [zenziva.id](http://www.zenziva.id/) with Laravel 5.3 onward.

## Contents

- [Installation](#installation)
    - [Setting up the Zenziva service](#setting-up-the-zenziva-notification-channel-service)
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
    TuxDaemon\ZenzivaNotificationChannel\ZenzivaServiceProvider::class,
],
```

Additionally you can add related facade in `config/app.php` :
```php
// config/app.php
'aliases' => [
    ...
    'ZenzivaClient' => TuxDaemon\ZenzivaNotificationChannel\Facades\ZenzivaFacade::class,
],
```

### Setting up the zenziva-notification-channel service

Add your Zenziva account userkey and passkeyto your `config/services.php`:

```php
// config/services.php
...
'zenziva' => [
    'userkey' => env('ZENZIVA_SMS_CLIENT_USERKEY', ''),
    'passkey' => env('ZENZIVA_SMS_CLIENT_PASSKEY', ''),
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
        return ZenzivaMessage::create("Your order had created!");
    }
}
```

Or call the function from facade :
```php
use ZenzivaClient;

class Something
{
    public function send($to, $msg)
    {
        return ZenzivaClient::send("081234567890", "hello world");
    }

    public function checkBalance()
    {
        return ZenzivaClient::checkBalance();
    }
}
```

Or from CLI artisan command :
```bash
$ php artisan zenziva:send 081234567890 "hello world"
$ php artisan zenziva:checkbalance
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

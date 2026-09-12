# Telegram Notification

![License](https://img.shields.io/badge/license-CC-BY-ND-2.5-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/nodeloc/flarum-telegram-notification.svg)](https://packagist.org/packages/nodeloc/flarum-telegram-notification) [![Total Downloads](https://img.shields.io/packagist/dt/nodeloc/flarum-telegram-notification.svg)](https://packagist.org/packages/nodeloc/flarum-telegram-notification)

A [Flarum](http://flarum.org) extension that pushes new discussions to a Telegram channel.

The `flarum-2` branch targets Flarum 2.0.0-rc.8 and later Flarum 2.0 releases.

## Installation

The Flarum 2 branch is currently installed from the fork's VCS repository:

```bash
composer config repositories.lowseekai-telegram vcs https://github.com/lowseekai/flarum-telegram-notification
composer require nodeloc/flarum-telegram-notification:dev-flarum-2 -W
```

Then clear the Flarum cache:

```bash
php flarum migrate
php flarum cache:clear
```

## Configuration

Configure the bot token, Telegram channel ID, and optional excluded tag IDs in the extension settings.

The bot must have permission to post in the target channel. If the forum uses an asynchronous queue driver, run a queue worker so notifications are delivered.

## Links

- [Packagist](https://packagist.org/packages/nodeloc/flarum-telegram-notification)
- [GitHub](https://github.com/nodeloc/flarum-telegram-notification)
- [Discuss](https://discuss.flarum.org/d/PUT_DISCUSS_SLUG_HERE)

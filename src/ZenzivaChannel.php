<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

use Illuminate\Notifications\Notification;
use TuxDaemon\ZenzivaNotificationChannel\Exceptions\ZenzivaException;

class ZenzivaChannel
{
    /** @var ZenzivaClient */
    protected $zenziva;

    /**
     * @param ZenzivaClient $zenziva
     */
    public function __construct(ZenzivaClient $zenziva)
    {
        $this->zenziva = $zenziva;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @throws ZenzivaException
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('zenziva')) {
            return;
        }

        $message = $notification->toZenziva($notifiable);

        if (is_string($message)) {
            $message = new ZenzivaMessage($message);
        }

        $this->zenziva->send($to, $message->getContent());
    }
}

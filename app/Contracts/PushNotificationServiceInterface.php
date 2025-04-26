<?php

namespace App\Contracts;

interface PushNotificationServiceInterface
{
    public static function sendPushyNotification($deviceToken, $title, $message);
}

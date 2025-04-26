<?php

namespace App\Services;

use App\Contracts\PushNotificationServiceInterface;
use Illuminate\Support\Facades\Http;


class PushyNotificationService implements PushNotificationServiceInterface
{
    public static function sendPushyNotification($deviceToken, $title, $message)
    {
        $payload = [
            'to' => $deviceToken,
            'data' => [
                'title' => $title,
                'message' => $message,
            ],
            "notification" => [
                "title" => $title,
                "body" =>  $title,
                "badge" => 1,
                "sound" => "ping.aiff"
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('PUSHY_API_KEY'),
        ])->post('https://api.pushy.me/push?api_key=' . env('PUSHY_API_KEY'), $payload);

        return $response->json();
    }
}

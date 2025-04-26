<?php

namespace App\Services;

use Junges\Kafka\Facades\Kafka;
use App\Enums\KafkaTopics;


class KafkaEmailPublisherService
{
    public function publish(string $email, string $subject, string $body): void
    {
        Kafka::publishOn(KafkaTopics::EMAIL_SEND_REQUESTS->value)
            ->withBodyKey('data', [
                'email' => $email,
                'subject' => $subject,
                'body' => $body,
            ])
            ->send();
    }
}

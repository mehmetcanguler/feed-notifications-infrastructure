<?php

namespace App\Services;

use App\Enums\KafkaTopics;
use Junges\Kafka\Facades\Kafka;

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

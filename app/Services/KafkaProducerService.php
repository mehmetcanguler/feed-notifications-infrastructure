<?php

namespace App\Services;

use App\Contracts\KafkaProducerServiceInterface;
use App\DataTransferObjects\KafkaMessageData;
use App\Enums\KafkaTopics;
use Junges\Kafka\Facades\Kafka;

class KafkaProducerService implements KafkaProducerServiceInterface
{
    public function produce(KafkaTopics $topic, KafkaMessageData $data): void
    {
        Kafka::publish()
            ->onTopic($topic->value)
            ->withBodyKey('data', $data->toArray())
            ->send();
    }
}

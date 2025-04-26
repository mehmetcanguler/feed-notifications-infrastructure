<?php

namespace App\Contracts;

use App\DataTransferObjects\KafkaMessageData;
use App\Enums\KafkaTopics;

interface KafkaProducerServiceInterface
{
    public function produce(KafkaTopics $topic, KafkaMessageData $data): void;
}

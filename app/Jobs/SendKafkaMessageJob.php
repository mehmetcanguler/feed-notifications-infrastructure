<?php

namespace App\Jobs;

use App\Contracts\KafkaProducerServiceInterface;
use App\DataTransferObjects\KafkaMessageData;
use App\Enums\KafkaTopics;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendKafkaMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected KafkaTopics $topic,
        protected KafkaMessageData $data
    ) {}

    public function handle(KafkaProducerServiceInterface $kafkaProducer): void
    {
        $kafkaProducer->produce($this->topic, $this->data);
    }
}

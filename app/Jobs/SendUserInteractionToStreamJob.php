<?php

namespace App\Jobs;

use App\DataTransferObjects\KafkaMessageData;
use App\Enums\RedisStreams;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;

class SendUserInteractionToStreamJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected KafkaMessageData $data
    ) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $command = [
            'XADD',
            RedisStreams::USER_INTERACTIONS->value,
            '*',
        ];

        $fields = [
            'user_id' => (string) $this->data->userId,
            'action' => (string) $this->data->action,
            'platform' => (string) $this->data->platform,
            'target_type' => (string) $this->data->targetType,
            'target_id' => (string) $this->data->targetId,
            'metadata' => json_encode($this->data->metadata),
            'timestamp' => (string) $this->data->timestamp,
        ];

        foreach ($fields as $key => $value) {
            $command[] = $key;
            $command[] = $value;
        }

        Redis::executeRaw($command);

    }
}

<?php

namespace App\DataTransferObjects;

class KafkaMessageData
{
    public function __construct(
        public int $userId,
        public string $action,
        public string $platform,
        public string $targetType,
        public int $targetId,
        public array $metadata,
        public string $timestamp
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'action' => $this->action,
            'platform' => $this->platform,
            'target_type' => $this->targetType,
            'target_id' => $this->targetId,
            'metadata' => $this->metadata,
            'timestamp' => $this->timestamp,
        ];
    }
}

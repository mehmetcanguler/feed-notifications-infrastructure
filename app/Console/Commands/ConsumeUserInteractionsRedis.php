<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Enums\RedisStreams;
use Throwable;

class ConsumeUserInteractionsRedis extends Command
{
    protected $signature = 'stream:listen 
                            {--group=user-interaction-group : Consumer group name}
                            {--consumer=consumer1 : Consumer name}
                            {--count=100 : Number of messages to read per batch}
                            {--block=5000 : Block time in milliseconds}';

    protected $description = 'Consume messages from Redis Stream for user interactions';

    public function handle()
    {
        $this->initializeConsumerGroup();

        $lastId = '>';
        $stream = RedisStreams::USER_INTERACTIONS->value;

        while (true) {
            try {
                $messages = $this->fetchMessages($stream, $lastId);

                if (empty($messages)) {
                    continue;
                }

                foreach ($messages[0][1] as $message) {
                    $this->processMessage($message, $stream);
                }

            } catch (Throwable $e) {
                $this->handleError($e);
                sleep(5);
            }
        }
    }

    private function initializeConsumerGroup(): void
    {
        try {
            Redis::executeRaw([
                'XGROUP', 'CREATE', 
                RedisStreams::USER_INTERACTIONS->value, 
                $this->option('group'), 
                '0', 
                'MKSTREAM'
            ]);
        } catch (Throwable $e) {
            $this->warn('Consumer group already exists: ' . $e->getMessage());
        }
    }

    private function fetchMessages(string $stream, string &$lastId): array
    {
        return Redis::executeRaw([
            'XREADGROUP', 'GROUP', 
            $this->option('group'), 
            $this->option('consumer'), 
            'COUNT', $this->option('count'), 
            'BLOCK', $this->option('block'), 
            'STREAMS', $stream, $lastId
        ]) ?? [];
    }

    private function processMessage(array $message, string $stream): void
    {
        $messageId = $message[0];
        $entries = $this->parseStreamEntry($message[1]);

        try {
            $this->displayMessage($entries);
            $this->handleBusinessLogic($entries);
            $this->acknowledgeMessage($stream, $messageId);
        } catch (Throwable $e) {
            $this->handleMessageError($e, $messageId, $entries);
        }
    }

    private function parseStreamEntry(array $entry): array
    {
        $result = [];
        for ($i = 0; $i < count($entry); $i += 2) {
            $key = $entry[$i];
            $value = $entry[$i + 1];
            $result[$key] = $this->parseValue($key, $value);
        }
        return $result;
    }

    private function parseValue(string $key, mixed $value): mixed
    {
        return match($key) {
            'metadata' => json_decode($value, true) ?? [],
            'user_id', 'target_id' => (int)$value,
            'timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $value),
            default => $value
        };
    }

    private function displayMessage(array $data): void
    {
        $this->info("\n📩 New Message Received: " . now()->toDateTimeString());
        
        $tableData = [];
        foreach ($data as $key => $value) {
            $tableData[] = [
                'Field' => $this->formatFieldName($key),
                'Value' => $this->formatValue($key, $value)
            ];
        }

        $this->table(['Field', 'Value'], $tableData);
    }

    private function formatFieldName(string $field): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $field));
    }

    private function formatValue(string $key, mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }

        return (string)$value;
    }

    private function handleBusinessLogic(array $data): void
    {
        // Örnek iş mantığı - Kendi kodunuzla değiştirin
        // $this->saveToDatabase($data);
        // $this->sendToExternalService($data);
    }

    private function acknowledgeMessage(string $stream, string $messageId): void
    {
        Redis::executeRaw([
            'XACK', 
            $stream, 
            $this->option('group'), 
            $messageId
        ]);
    }

    private function handleError(Throwable $e): void
    {
        $this->error('[❗] System Error: ' . $e->getMessage());
        logger()->error('Stream Consumer Error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    private function handleMessageError(Throwable $e, string $messageId, array $data): void
    {
        $this->error("[❗] Message Processing Failed (ID: $messageId): " . $e->getMessage());
        logger()->error('Message Processing Error', [
            'message_id' => $messageId,
            'data' => $data,
            'error' => $e->getMessage()
        ]);
    }
}
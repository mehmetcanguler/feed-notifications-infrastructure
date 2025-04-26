<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;

class ConsumeUserInteractionsKafka extends Command
{
    protected $signature = 'kafka:listen';

    protected $description = 'Kafka" üzerindeki mesajları tdinler';

    public function handle()
    {
        $this->info('🔄 Kafka Consumer başlatıldı, mesajlar dinleniyor...');

        Kafka::consumer()
            ->subscribe('user-interactions')
            ->withHandler(function (ConsumedMessage $message) {
                $payload = $message->getBody();

                $prettyPayload = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                $this->info('✅ Gelen Mesaj:');
                $this->line($prettyPayload);

            })
            ->build()
            ->consume();
    }
}

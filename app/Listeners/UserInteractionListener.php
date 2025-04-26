<?php

namespace App\Listeners;

use App\Contracts\UserInteractionServiceInterface;
use App\DataTransferObjects\KafkaMessageData;
use App\Enums\KafkaTopics;
use App\Events\UserInteractionCreated;
use App\Jobs\SendKafkaMessageJob;
use App\Jobs\SendUserInteractionToStreamJob;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserInteractionListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserInteractionCreated $event): void
    {
        $data = new KafkaMessageData(
            userId: $event->userInteraction->user_id,
            action: $event->userInteraction->user_action->name(),
            platform: $event->userInteraction->platform_type->name(),
            targetType: $event->userInteraction->target_type,
            targetId: $event->userInteraction->target_id,
            metadata: $event->userInteraction->metadata,
            timestamp: $event->userInteraction->created_at->toDateTimeString(),
        );
        // Kafka'ya gönder
        SendKafkaMessageJob::dispatch(KafkaTopics::USER_INTERACTIONS, $data);

        // Redis Stream'e ekle
        SendUserInteractionToStreamJob::dispatch( $data);
    }
}
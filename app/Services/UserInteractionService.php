<?php

namespace App\Services;

use App\Contracts\UserInteractionServiceInterface;
use App\DataTransferObjects\UserInteractionData;
use App\Events\UserInteractionCreated;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\Cache;

class UserInteractionService implements UserInteractionServiceInterface
{
    /**
     * Create a new user interaction and store it in the cache.
     */
    public function createInteraction(UserInteractionData $data): UserInteraction
    {
        $userInteraction = UserInteraction::create([
            'user_id' => $data->userId,
            'user_action' => $data->action,
            'platform_type' => $data->platform,
            'target_type' => $data->targetType,
            'target_id' => $data->targetId,
            'metadata' => $data->metadata,
        ]);

        $cacheKey = "user_interaction_{$userInteraction->user_id}";
        Cache::put($cacheKey, $userInteraction->toArray(), now()->addMinutes(10));

        event(new UserInteractionCreated($userInteraction));

        return $userInteraction;
    }

    /**
     * Get the user interaction from cache.
     */
    public function getUserInteractionFromCache(int $userId): ?UserInteraction
    {
        $cacheKey = "user_interaction_{$userId}";

        return Cache::get($cacheKey);
    }
}

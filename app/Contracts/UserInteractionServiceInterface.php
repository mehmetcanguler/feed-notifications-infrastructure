<?php

namespace App\Contracts;

use App\DataTransferObjects\UserInteractionData;
use App\Models\UserInteraction;

interface UserInteractionServiceInterface
{
    /**
     * Create a new user interaction.
     */
    public function createInteraction(UserInteractionData $data): UserInteraction;

    /**
     * Get the user interaction from cache.
     */
    public function getUserInteractionFromCache(int $userId): ?UserInteraction;
}

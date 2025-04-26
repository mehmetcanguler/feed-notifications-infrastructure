<?php

namespace App\Contracts;

use App\DataTransferObjects\UserInteractionData;
use App\Models\UserInteraction;

interface UserInteractionServiceInterface
{
    /**
     * Create a new user interaction.
     *
     * @param  \App\DataTransferObjects\UserInteractionData  $data
     * @return \App\Models\UserInteraction
     */
    public function createInteraction(UserInteractionData $data): UserInteraction;


    /**
     * Get the user interaction from cache.
     *
     * @param int $userId
     * @return \App\Models\UserInteraction|null
     */
    public function getUserInteractionFromCache(int $userId): ?UserInteraction;
}

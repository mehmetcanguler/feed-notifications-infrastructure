<?php

namespace App\Http\Controllers;

use App\Contracts\UserInteractionServiceInterface;
use App\DataTransferObjects\UserInteractionData;
use App\Http\Requests\UserInteractionStoreRequest;

class UserInteractionController extends Controller
{
    public function __construct(
        protected UserInteractionServiceInterface $userInteractionService
    ) {}

    public function store(UserInteractionStoreRequest $request)
    {
        $userInteractionData = UserInteractionData::fromRequest($request);

        $userInteraction = $this->userInteractionService
            ->createInteraction($userInteractionData);

        return $this->success($userInteraction);
    }
}

<?php

namespace App\DataTransferObjects;

use App\Enums\PlatformType;
use App\Enums\UserAction;
use Illuminate\Http\Request;

class UserInteractionData
{
    public function __construct(
        public int $userId,
        public UserAction $action,
        public PlatformType $platform,
        public string $targetType,
        public int $targetId,
        public array $metadata = [],
    ) {
        //
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validated();

        return new self(
            userId: (int) $validated['user_id'],
            action: UserAction::from((int) $validated['action']),
            platform: PlatformType::from((int) $validated['platform']),
            targetType: (string) $validated['target_type'],
            targetId: (int) $validated['target_id'],
            metadata: $validated['metadata'] ?? [],
        );
    }
}

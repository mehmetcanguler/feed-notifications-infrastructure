<?php

namespace Tests\Feature;

use App\Events\UserInteractionCreated;
use App\Models\User;
use App\Models\UserInteraction;
use App\Contracts\UserInteractionServiceInterface;
use App\DataTransferObjects\UserInteractionData;
use Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInteractionFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_interaction_creates_and_caches()
    {
        Event::fake();

        $user = User::factory()->create();

        $data = new UserInteractionData(
            userId: $user->id,
            action: \App\Enums\UserAction::CLICK,
            platform: \App\Enums\PlatformType::WEB,
            targetType: 'post',
            targetId: 123,
            metadata: []
        );

        $service = app(UserInteractionServiceInterface::class);
        $userInteraction = $service->createInteraction($data);

        $this->assertDatabaseHas('user_interactions', [
            'user_id' => $user->id,
            'target_type' => 'post',
            'target_id' => 123,
        ]);

        Event::assertDispatched(UserInteractionCreated::class);

        //test ortamında listeneri manuel çağırıyoruz

        $listener = app(\App\Listeners\UserInteractionListener::class);
        $listener->handle(new UserInteractionCreated($userInteraction));

        $cacheKey = "user_interaction_{$user->id}";
        $this->assertNotNull(Cache::get($cacheKey));

        $cachedData = Cache::get($cacheKey);

        $this->assertEquals($user->id, $cachedData['user_id']);
        $this->assertEquals('post', $cachedData['target_type']);
        $this->assertEquals(123, $cachedData['target_id']);
    }
}

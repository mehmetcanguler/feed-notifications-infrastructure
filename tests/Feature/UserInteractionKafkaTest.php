<?php

namespace Tests\Feature;

use App\Events\UserInteractionCreated;
use App\Jobs\SendKafkaMessageJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UserInteractionKafkaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_interaction_creates_database_event_and_job_dispatch()
    {
        Event::fake();
        Queue::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/api/user-interactions', [
            'user_id' => $user->id,
            'action' => 1,
            'platform' => 1,
            'target_type' => 'post',
            'target_id' => 123,
            'metadata' => ['sample_key' => 'sample_value'],
        ]);

        $response->assertCreated(); 

        $this->assertDatabaseHas('user_interactions', [
            'user_id' => $user->id,
            'target_type' => 'post',
            'target_id' => 123,
        ]);

        Event::assertDispatched(UserInteractionCreated::class);

        Queue::assertPushed(SendKafkaMessageJob::class);
    }
}

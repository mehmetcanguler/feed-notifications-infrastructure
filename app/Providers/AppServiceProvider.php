<?php

namespace App\Providers;

use App\Contracts\KafkaProducerServiceInterface;
use App\Contracts\PushNotificationServiceInterface;
use App\Contracts\UserInteractionServiceInterface;
use App\Services\KafkaProducerService;
use App\Services\PushyNotificationService;
use App\Services\UserInteractionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInteractionServiceInterface::class, UserInteractionService::class);
        $this->app->bind(KafkaProducerServiceInterface::class, KafkaProducerService::class);
        $this->app->bind(PushNotificationServiceInterface::class, PushyNotificationService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

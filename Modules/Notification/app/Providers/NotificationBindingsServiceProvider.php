<?php

namespace Modules\Notification\Providers;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Support\ServiceProvider;
use Modules\Notification\Application\Contracts\NotificationRepositoryInterface;
use Modules\Notification\Application\Contracts\NotificationServiceInterface;
use Modules\Notification\Application\Services\NotificationService;
use Modules\Notification\Infrastructure\Channels\FcmNotificationChannel;
use Modules\Notification\Infrastructure\Channels\WebPushNotificationChannel;
use Modules\Notification\Infrastructure\Repositories\NotificationRepository;

class NotificationBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);

        $this->app->singleton('notification.channels', function (): array {
            return [
                'fcm' => new FcmNotificationChannel(),
                'web_push' => new WebPushNotificationChannel(),
            ];
        });

        $this->app->bind(NotificationServiceInterface::class, function ($app): NotificationService {
            return new NotificationService(
                $app->make(NotificationRepositoryInterface::class),
                $app->make('notification.channels'),
                $app->make(TenantContextInterface::class),
            );
        });
    }
}

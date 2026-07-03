<?php

namespace Modules\Identity\Providers;

use Modules\Identity\Http\Middleware\CheckPermission;
use Modules\Identity\Http\Middleware\EnsureEmailVerified;
use Modules\Identity\Http\Middleware\EnsureTenantResolved;
use Modules\Identity\Http\Middleware\EnsureUserIsActive;
use Modules\Identity\Application\Contracts\AuthenticationServiceInterface;
use Modules\Identity\Application\Contracts\AuthorizationServiceInterface;
use Modules\Identity\Application\Contracts\CurrentUserServiceInterface;
use Modules\Identity\Application\Contracts\EmailVerificationServiceInterface;
use Modules\Identity\Application\Contracts\PasswordResetServiceInterface;
use Modules\Identity\Application\Contracts\RegistrationServiceInterface;
use Modules\Identity\Application\Interfaces\AuthenticatorInterface;
use Modules\Identity\Application\Interfaces\PasswordResetBrokerInterface;
use Modules\Identity\Application\Interfaces\PermissionCheckerInterface;
use Modules\Identity\Application\Services\AuthenticationService;
use Modules\Identity\Application\Services\AuthorizationService;
use Modules\Identity\Application\Services\CurrentUserService;
use Modules\Identity\Application\Services\EmailVerificationService;
use Modules\Identity\Application\Services\PasswordResetService;
use Modules\Identity\Application\Services\RegistrationService;
use Modules\Identity\Application\Support\LaravelAuthenticator;
use Modules\Identity\Application\Support\LaravelPasswordResetBroker;
use Modules\Identity\Application\Support\SpatiePermissionChecker;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Console\Scheduling\Schedule;

class IdentityServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->bind(AuthenticatorInterface::class, LaravelAuthenticator::class);
        $this->app->bind(PasswordResetBrokerInterface::class, LaravelPasswordResetBroker::class);
        $this->app->bind(PermissionCheckerInterface::class, SpatiePermissionChecker::class);

        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(RegistrationServiceInterface::class, RegistrationService::class);
        $this->app->bind(PasswordResetServiceInterface::class, PasswordResetService::class);
        $this->app->bind(EmailVerificationServiceInterface::class, EmailVerificationService::class);
        $this->app->bind(AuthorizationServiceInterface::class, AuthorizationService::class);
        $this->app->bind(CurrentUserServiceInterface::class, CurrentUserService::class);
    }

    public function boot(): void
    {
        parent::boot();

        RateLimiter::for('identity-auth-login', function (Request $request) {
            return Limit::perMinute(5)->by($this->identityRateLimitKey($request));
        });

        RateLimiter::for('identity-auth-register', function (Request $request) {
            return Limit::perMinute(5)->by($this->identityRateLimitKey($request));
        });

        RateLimiter::for('identity-auth-forgot-password', function (Request $request) {
            return Limit::perMinute(3)->by($this->identityRateLimitKey($request));
        });

        RateLimiter::for('identity-auth-reset-password', function (Request $request) {
            return Limit::perMinute(3)->by($this->identityRateLimitKey($request));
        });

        RateLimiter::for('identity-auth-verify-email', function (Request $request) {
            return Limit::perMinute(6)->by($this->identityRateLimitKey($request));
        });

        /** @var Router $router */
        $router = $this->app->make('router');
        $router->aliasMiddleware('identity.tenant', EnsureTenantResolved::class);
        $router->aliasMiddleware('identity.user.active', EnsureUserIsActive::class);
        $router->aliasMiddleware('identity.email.verified', EnsureEmailVerified::class);
        $router->aliasMiddleware('identity.permission', CheckPermission::class);
    }

    private function identityRateLimitKey(Request $request): string
    {
        $tenantId = $request->attributes->get('tenant_id', 'system');

        return sprintf(
            '%s|%s|%s',
            $tenantId,
            mb_strtolower((string) $request->input('email', 'guest')),
            (string) $request->ip(),
        );
    }

    /**
     * The name of the module.
     */
    protected string $name = 'Identity';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'identity';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}

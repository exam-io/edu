<?php

namespace Modules\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Modules\Identity\Application\Contracts\AuthenticationServiceInterface;
use Modules\Identity\Application\Contracts\AuthorizationServiceInterface;
use Modules\Identity\Application\Contracts\CurrentUserServiceInterface;
use Modules\Identity\Application\Contracts\EmailVerificationServiceInterface;
use Modules\Identity\Application\Contracts\PasswordResetServiceInterface;
use Modules\Identity\Application\Contracts\RegistrationServiceInterface;
use Modules\Identity\Application\DTOs\EmailVerificationData;
use Modules\Identity\Application\DTOs\LoginData;
use Modules\Identity\Application\DTOs\PasswordResetData;
use Modules\Identity\Application\DTOs\PasswordResetLinkData;
use Modules\Identity\Application\DTOs\RegistrationData;
use Modules\Identity\Events\EmailVerified;
use Modules\Identity\Events\PasswordResetRequested;
use Modules\Identity\Events\UserLoggedIn;
use Modules\Identity\Events\UserLoggedOut;
use Modules\Identity\Events\UserRegistered;
use Modules\Identity\Http\Requests\ForgotPasswordRequest;
use Modules\Identity\Http\Requests\LoginRequest;
use Modules\Identity\Http\Requests\RegisterRequest;
use Modules\Identity\Http\Requests\ResetPasswordRequest;
use Modules\Identity\Http\Requests\VerifyEmailRequest;
use Modules\Identity\Http\Resources\AuthContextResource;
use Modules\Identity\Http\Resources\AuthUserResource;
use Modules\Identity\Http\Responses\ApiResponse;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticationServiceInterface $authenticationService,
        private readonly RegistrationServiceInterface $registrationService,
        private readonly PasswordResetServiceInterface $passwordResetService,
        private readonly EmailVerificationServiceInterface $emailVerificationService,
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly AuthorizationServiceInterface $authorizationService,
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $tenantId = $this->resolveTenantId($request);

            if ($tenantId === null) {
                return ApiResponse::error('Tenant context is required for registration.', status: 422);
            }

            $user = $this->registrationService->register(new RegistrationData(
                tenantId: $tenantId,
                name: $request->string('name')->toString(),
                email: $request->string('email')->toString(),
                password: $request->string('password')->toString(),
                status: 'active',
                role: null,
                language: $request->string('language')->toString(),
                theme: $request->string('theme')->toString(),
                timezone: $request->string('timezone')->toString(),
            ));

            Event::dispatch(new UserRegistered(
                userId: $user->id,
                tenantId: $user->tenant_id,
                email: $user->email,
                name: $user->name,
                language: $request->string('language')->toString(),
                theme: $request->string('theme')->toString(),
                timezone: $request->string('timezone')->toString(),
            ));

            $token = $user->createToken('auth')->plainTextToken;

            return ApiResponse::success(
                message: 'Registration successful.',
                data: [
                    'user' => (new AuthUserResource($user->loadMissing(['tenant', 'settings'])))->resolve(),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                status: 201,
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Registration failed.', status: 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $tenantId = $this->resolveTenantId($request);

            $user = $this->authenticationService->authenticate(new LoginData(
                email: $request->string('email')->toString(),
                password: $request->string('password')->toString(),
                tenantId: $tenantId,
                remember: $request->boolean('remember'),
                ipAddress: $request->ip(),
            ));

            if ($user === null) {
                return ApiResponse::error('Invalid credentials.', status: 401);
            }

            Event::dispatch(new UserLoggedIn(
                userId: $user->id,
                tenantId: $user->tenant_id,
                ipAddress: $request->ip(),
            ));

            $token = $user->createToken('auth')->plainTextToken;

            return ApiResponse::success(
                message: 'Login successful.',
                data: [
                    'user' => (new AuthUserResource($user->loadMissing(['tenant', 'settings'])))->resolve(),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            );
        } catch (Throwable $e) {
            return ApiResponse::error('Login failed.', status: 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user !== null) {
                Event::dispatch(new UserLoggedOut(
                    userId: $user->id,
                    tenantId: $user->tenant_id,
                ));
            }

            $token = $request->user()?->currentAccessToken();

            if ($token !== null && method_exists($token, 'delete')) {
                $token->delete();
            }

            $this->authenticationService->logout();

            return ApiResponse::success('Logout successful.');
        } catch (Throwable $e) {
            Log::error('Auth logout failed.', [
                'exception' => $e,
                'message' => $e->getMessage(),
            ]);

            return ApiResponse::error('Logout failed.', status: 500);
        }
    }

    public function me(): JsonResponse
    {
        try {
            $user = $this->currentUserService->userOrFail()->loadMissing(['tenant', 'settings']);

            return ApiResponse::success('Authenticated user fetched successfully.', (new AuthUserResource($user))->resolve());
        } catch (AuthenticationException $e) {
            return ApiResponse::error('Unauthenticated.', status: 401);
        } catch (Throwable $e) {
            return ApiResponse::error('Unable to fetch current user.', status: 500);
        }
    }

    public function context(): JsonResponse
    {
        try {
            $user = $this->currentUserService->userOrFail()->loadMissing(['tenant', 'settings']);

            $context = new AuthContextResource([
                'user' => $user,
                'can_manage_identity' => $this->authorizationService->can(
                    $user,
                    new \Modules\Identity\Application\DTOs\AuthorizationCheckData(role: 'Super Admin'),
                ),
            ]);

            return ApiResponse::success('Authentication context fetched successfully.', $context->resolve());
        } catch (AuthenticationException $e) {
            return ApiResponse::error('Unauthenticated.', status: 401);
        } catch (Throwable $e) {
            return ApiResponse::error('Unable to fetch authentication context.', status: 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $status = $this->passwordResetService->sendResetLink(new PasswordResetLinkData(
                email: $request->string('email')->toString(),
            ));

            if ($status !== Password::RESET_LINK_SENT) {
                return ApiResponse::error('Unable to send password reset link.', ['status' => $status], 422);
            }

            Event::dispatch(new PasswordResetRequested(
                email: $request->string('email')->toString(),
                tenantId: $this->resolveTenantId($request),
            ));

            return ApiResponse::success('Password reset link sent successfully.', ['status' => $status]);
        } catch (Throwable $e) {
            return ApiResponse::error('Password reset link request failed.', status: 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $status = $this->passwordResetService->resetPassword(new PasswordResetData(
                email: $request->string('email')->toString(),
                token: $request->string('token')->toString(),
                password: $request->string('password')->toString(),
            ));

            if ($status !== Password::PASSWORD_RESET) {
                return ApiResponse::error('Unable to reset password.', ['status' => $status], 422);
            }

            return ApiResponse::success('Password reset successfully.', ['status' => $status]);
        } catch (Throwable $e) {
            return ApiResponse::error('Password reset failed.', status: 500);
        }
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        try {
            $authenticatedUser = $this->currentUserService->userOrFail();

            if ($authenticatedUser->id !== $request->integer('user_id')) {
                return ApiResponse::error('Email verification target mismatch.', status: 403);
            }

            $verified = $this->emailVerificationService->verify(new EmailVerificationData(
                userId: $request->integer('user_id'),
                emailHash: $request->string('hash')->toString(),
                tenantId: $this->resolveTenantId($request),
            ));

            if (! $verified) {
                return ApiResponse::error('Email verification failed.', status: 422);
            }

            Event::dispatch(new EmailVerified(
                userId: $request->integer('user_id'),
                tenantId: $this->resolveTenantId($request),
            ));

            return ApiResponse::success('Email verified successfully.');
        } catch (Throwable $e) {
            return ApiResponse::error('Email verification failed.', status: 500);
        }
    }

    private function resolveTenantId(Request $request): ?int
    {
        $resolvedTenantId = $request->attributes->get('tenant_id');

        if ($resolvedTenantId !== null) {
            return (int) $resolvedTenantId;
        }

        $inputTenantId = $request->input('tenant_id');

        if ($inputTenantId !== null && $inputTenantId !== '') {
            return (int) $inputTenantId;
        }

        return null;
    }
}

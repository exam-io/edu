export { LoginPage } from '@modules/auth/pages/LoginPage';
export { ForgotPasswordPage } from '@modules/auth/pages/ForgotPasswordPage';
export { ResetPasswordPage } from '@modules/auth/pages/ResetPasswordPage';
export { VerifyEmailPage } from '@modules/auth/pages/VerifyEmailPage';

export { AuthProvider } from '@modules/auth/components/AuthProvider';
export { PermissionProvider } from '@modules/auth/components/PermissionProvider';
export { TenantProvider } from '@modules/auth/components/TenantProvider';

export { GuestRoute } from '@modules/auth/routes/GuestRoute';
export { ProtectedRoute } from '@modules/auth/routes/ProtectedRoute';
export { PermissionRoute } from '@modules/auth/routes/PermissionRoute';

export { useAuth } from '@modules/auth/hooks/useAuth';
export { usePermission } from '@modules/auth/hooks/usePermission';
export { useTenant } from '@modules/auth/hooks/useTenant';

export { authService } from '@modules/auth/services/authService';
export { useAuthStore } from '@modules/auth/store/authStore';

export type {
	AuthContext,
	AuthEnvelope,
	AuthSettings,
	AuthTenant,
	AuthUser,
	LoginPayload,
	RegisterPayload,
	ResetPasswordPayload,
	VerifyEmailPayload,
} from '@modules/auth/types/auth';

import axios, { AxiosError } from 'axios';
import type {
    AuthContext,
    AuthEnvelope,
    LoginPayload,
    RegisterPayload,
    ResetPasswordPayload,
    VerifyEmailPayload,
} from '@modules/auth/types/auth';

interface LoginResponse {
    user: AuthContext['user'];
    token: string;
    token_type: string;
}

const authHttp = axios.create({
    baseURL: '/api/auth',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
});

const tokenStorageKey = 'eduos:auth:token';

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<AuthEnvelope<unknown>> | undefined;

    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: AuthEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const authService = {
    getStoredToken(): string | null {
        return localStorage.getItem(tokenStorageKey);
    },

    setToken(token: string | null): void {
        if (token === null || token === '') {
            localStorage.removeItem(tokenStorageKey);
            delete authHttp.defaults.headers.common.Authorization;
            return;
        }

        localStorage.setItem(tokenStorageKey, token);
        authHttp.defaults.headers.common.Authorization = `Bearer ${token}`;
    },

    async initializeCsrf(): Promise<void> {
        await axios.get('/sanctum/csrf-cookie', {
            withCredentials: true,
            headers: {
                Accept: 'application/json',
            },
        });
    },

    async login(payload: LoginPayload): Promise<LoginResponse> {
        try {
            await this.initializeCsrf();
            const response = await authHttp.post<AuthEnvelope<LoginResponse>>('/login', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async register(payload: RegisterPayload): Promise<LoginResponse> {
        try {
            await this.initializeCsrf();
            const response = await authHttp.post<AuthEnvelope<LoginResponse>>('/register', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async logout(): Promise<void> {
        try {
            await authHttp.post<AuthEnvelope<null>>('/logout');
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async fetchContext(): Promise<AuthContext> {
        try {
            const response = await authHttp.get<AuthEnvelope<AuthContext>>('/context');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async forgotPassword(email: string): Promise<void> {
        try {
            await this.initializeCsrf();
            await authHttp.post<AuthEnvelope<{ status: string }>>('/forgot-password', { email });
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async resetPassword(payload: ResetPasswordPayload): Promise<void> {
        try {
            await this.initializeCsrf();
            await authHttp.post<AuthEnvelope<{ status: string }>>('/reset-password', payload);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async verifyEmail(payload: VerifyEmailPayload): Promise<void> {
        try {
            await authHttp.post<AuthEnvelope<null>>('/verify-email', payload);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};

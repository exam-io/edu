import { FormEvent, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';

export function LoginPage() {
    const login = useAuthStore((state) => state.login);
    const loading = useAuthStore((state) => state.loading);
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const navigate = useNavigate();

    async function onSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        setError(null);

        try {
            await login({ email, password, remember });
            navigate('/');
        } catch (requestError) {
            setError(requestError instanceof Error ? requestError.message : 'Login failed.');
        }
    }

    return (
        <main className="mx-auto flex min-h-screen w-full max-w-md items-center px-6 py-10">
            <section className="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-6 shadow-sm">
                <h1 className="text-2xl font-semibold">Sign in</h1>
                <p className="mt-2 text-sm text-[var(--color-muted)]">Access your EduOS account.</p>

                <form className="mt-6 space-y-4" onSubmit={onSubmit}>
                    <label className="block text-sm">
                        Email
                        <input
                            className="mt-1 w-full rounded-lg border border-[var(--color-border)] bg-transparent px-3 py-2"
                            type="email"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                            required
                        />
                    </label>

                    <label className="block text-sm">
                        Password
                        <input
                            className="mt-1 w-full rounded-lg border border-[var(--color-border)] bg-transparent px-3 py-2"
                            type="password"
                            value={password}
                            onChange={(event) => setPassword(event.target.value)}
                            required
                        />
                    </label>

                    <label className="flex items-center gap-2 text-sm text-[var(--color-muted)]">
                        <input type="checkbox" checked={remember} onChange={(event) => setRemember(event.target.checked)} />
                        Remember me
                    </label>

                    {error ? <p className="text-sm text-red-600">{error}</p> : null}

                    <button
                        className="w-full rounded-lg bg-[var(--color-primary)] px-4 py-2 font-medium text-white disabled:opacity-60"
                        type="submit"
                        disabled={loading}
                    >
                        {loading ? 'Signing in...' : 'Sign in'}
                    </button>
                </form>
            </section>
        </main>
    );
}

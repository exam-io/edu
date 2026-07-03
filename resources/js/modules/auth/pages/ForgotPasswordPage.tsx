import { FormEvent, useState } from 'react';
import { authService } from '@modules/auth/services/authService';

export function ForgotPasswordPage() {
    const [email, setEmail] = useState('');
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    async function onSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        setLoading(true);
        setError(null);
        setMessage(null);

        try {
            await authService.forgotPassword(email);
            setMessage('Password reset link sent.');
        } catch (requestError) {
            setError(requestError instanceof Error ? requestError.message : 'Request failed.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <main className="mx-auto flex min-h-screen w-full max-w-md items-center px-6 py-10">
            <section className="w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-6 shadow-sm">
                <h1 className="text-2xl font-semibold">Forgot password</h1>
                <p className="mt-2 text-sm text-[var(--color-muted)]">Request a reset link for your account.</p>

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

                    {message ? <p className="text-sm text-emerald-600">{message}</p> : null}
                    {error ? <p className="text-sm text-red-600">{error}</p> : null}

                    <button
                        className="w-full rounded-lg bg-[var(--color-primary)] px-4 py-2 font-medium text-white disabled:opacity-60"
                        type="submit"
                        disabled={loading}
                    >
                        {loading ? 'Submitting...' : 'Send reset link'}
                    </button>
                </form>
            </section>
        </main>
    );
}

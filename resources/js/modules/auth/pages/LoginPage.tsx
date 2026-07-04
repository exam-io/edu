import { FormEvent, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { ArrowRight, BookOpenCheck, GraduationCap, LockKeyhole, Mail } from 'lucide-react';
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
        <main className="relative min-h-screen overflow-hidden px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <div className="pointer-events-none absolute inset-0 opacity-80">
                <div className="absolute -left-28 -top-20 h-72 w-72 rounded-full bg-[color-mix(in_oklab,var(--color-primary)_28%,transparent)] blur-3xl" />
                <div className="absolute -right-24 top-1/3 h-80 w-80 rounded-full bg-[color-mix(in_oklab,var(--color-secondary)_26%,transparent)] blur-3xl" />
                <div className="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-[color-mix(in_oklab,var(--color-accent)_16%,transparent)] blur-3xl" />
            </div>

            <div className="relative mx-auto grid w-full max-w-6xl items-center gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <section className="hidden rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[color-mix(in_oklab,var(--color-surface)_88%,transparent)] p-8 shadow-[var(--shadow-elevated)] backdrop-blur lg:block">
                    <span className="inline-flex items-center gap-2 rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--color-muted)]">
                        <GraduationCap size={14} /> EduOS
                    </span>
                    <h1 className="mt-4 text-4xl font-semibold leading-tight">
                        Welcome back to your
                        <span className="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] bg-clip-text text-transparent"> modern education workspace</span>
                    </h1>
                    <p className="mt-4 max-w-xl text-sm text-[var(--color-muted)]">
                        Manage classes, assessments, AI workflows, calendars, and live sessions from one premium operating system.
                    </p>

                    <div className="mt-8 grid gap-3 sm:grid-cols-2">
                        <article className="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                            <p className="text-xs uppercase tracking-[0.12em] text-[var(--color-muted)]">Institute Operations</p>
                            <p className="mt-2 text-lg font-semibold">12,860 Students</p>
                        </article>
                        <article className="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                            <p className="text-xs uppercase tracking-[0.12em] text-[var(--color-muted)]">AI Productivity</p>
                            <p className="mt-2 text-lg font-semibold">48 Jobs Completed</p>
                        </article>
                    </div>

                    <div className="mt-6 rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="inline-flex items-center gap-2 text-sm font-medium">
                            <BookOpenCheck size={16} className="text-[var(--color-primary)]" />
                            Today's Focus
                        </p>
                        <p className="mt-2 text-sm text-[var(--color-muted)]">Prepare Grade 10 revision notes and publish weekend assignments.</p>
                    </div>
                </section>

                <section className="w-full rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[color-mix(in_oklab,var(--color-surface)_90%,transparent)] p-6 shadow-[var(--shadow-elevated)] backdrop-blur sm:p-8">
                    <div className="mb-5">
                        <h2 className="text-2xl font-semibold sm:text-3xl">Sign in</h2>
                        <p className="mt-2 text-sm text-[var(--color-muted)]">Access your EduOS account with secure credentials.</p>
                    </div>

                    <form className="space-y-4" onSubmit={onSubmit}>
                        <label className="block text-sm font-medium">
                            Email
                            <span className="mt-1.5 flex items-center gap-2 rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 transition focus-within:border-[var(--color-primary)] focus-within:ring-2 focus-within:ring-[color-mix(in_oklab,var(--color-primary)_24%,transparent)]">
                                <Mail size={15} className="text-[var(--color-muted)]" />
                                <input
                                    className="w-full bg-transparent text-sm outline-none"
                                    type="email"
                                    value={email}
                                    onChange={(event) => setEmail(event.target.value)}
                                    placeholder="you@institute.edu"
                                    autoComplete="email"
                                    required
                                />
                            </span>
                        </label>

                        <label className="block text-sm font-medium">
                            Password
                            <span className="mt-1.5 flex items-center gap-2 rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 transition focus-within:border-[var(--color-primary)] focus-within:ring-2 focus-within:ring-[color-mix(in_oklab,var(--color-primary)_24%,transparent)]">
                                <LockKeyhole size={15} className="text-[var(--color-muted)]" />
                                <input
                                    className="w-full bg-transparent text-sm outline-none"
                                    type="password"
                                    value={password}
                                    onChange={(event) => setPassword(event.target.value)}
                                    placeholder="Enter password"
                                    autoComplete="current-password"
                                    required
                                />
                            </span>
                        </label>

                        <div className="flex flex-wrap items-center justify-between gap-2 pt-1">
                            <label className="inline-flex items-center gap-2 text-sm text-[var(--color-muted)]">
                                <input
                                    type="checkbox"
                                    checked={remember}
                                    onChange={(event) => setRemember(event.target.checked)}
                                    className="h-4 w-4 rounded border-[var(--color-border)]"
                                />
                                Keep me signed in
                            </label>
                            <button
                                type="button"
                                className="text-xs font-semibold uppercase tracking-[0.1em] text-[var(--color-primary)] hover:opacity-80"
                                onClick={() => navigate('/auth/forgot-password')}
                            >
                                Forgot password?
                            </button>
                        </div>

                        {error ? <p className="rounded-lg bg-rose-500/10 px-3 py-2 text-sm text-rose-700 dark:text-rose-300">{error}</p> : null}

                        <button className="btn-primary w-full justify-center" type="submit" disabled={loading}>
                            {loading ? 'Signing in...' : 'Sign in'}
                            {!loading ? <ArrowRight size={15} /> : null}
                        </button>
                    </form>

                    <p className="mt-5 text-center text-xs text-[var(--color-muted)]">
                        Secure access protected by role-aware permissions and tenant-aware authentication.
                    </p>
                </section>
            </div>
        </main>
    );
}

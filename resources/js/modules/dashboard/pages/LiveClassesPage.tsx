import { useEffect } from 'react';
import { useLiveClassStore } from '@modules/live-class/store/liveClassStore';

export function LiveClassesPage() {
    const sessions = useLiveClassStore((state) => state.sessions);
    const loading = useLiveClassStore((state) => state.loading);
    const error = useLiveClassStore((state) => state.error);
    const fetchSessions = useLiveClassStore((state) => state.fetchSessions);
    const joinSession = useLiveClassStore((state) => state.joinSession);

    useEffect(() => {
        void fetchSessions();
    }, [fetchSessions]);

    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Live Classes</h2>
                <p className="text-sm text-[var(--color-muted)]">Join classes instantly, monitor attendance, and access resources or chat.</p>
            </header>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading live classes...</p> : null}
            {error ? <p className="text-sm text-rose-600">{error}</p> : null}

            <div className="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                {sessions.map((session) => {
                    const status = session.status === 'scheduled' ? 'upcoming' : session.status;
                    const canJoin = session.status === 'live';

                    return (
                        <article key={session.id} className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                            <div className="flex items-start justify-between gap-2">
                                <div>
                                    <p className="text-sm font-semibold">{session.title}</p>
                                    <p className="mt-1 text-xs text-[var(--color-muted)]">Host #{session.host_user_id}</p>
                                </div>
                                <span className="rounded-full bg-emerald-500/15 px-2 py-1 text-[11px] font-semibold uppercase text-emerald-700 dark:text-emerald-300">
                                    {status}
                                </span>
                            </div>

                            <p className="mt-3 text-xs text-[var(--color-muted)]">
                                {new Date(session.scheduled_start_at).toLocaleString()} - {new Date(session.scheduled_end_at).toLocaleString()}
                            </p>

                            <div className="mt-3 flex items-center gap-2">
                                <button type="button" className="btn-primary text-xs" disabled={!canJoin} onClick={() => void joinSession(session.id)}>
                                    Join
                                </button>
                                <a
                                    href={session.meeting_url ?? '#'}
                                    className="btn-ghost text-xs"
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-disabled={!session.meeting_url}
                                >
                                    Open Link
                                </a>
                            </div>
                        </article>
                    );
                })}
            </div>
        </section>
    );
}

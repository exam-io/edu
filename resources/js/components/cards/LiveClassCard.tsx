import { Clock3, Users } from 'lucide-react';

interface LiveClassCardProps {
    subject: string;
    teacher: string;
    attendees: number;
    schedule: string;
    status: 'live' | 'upcoming' | 'ended';
}

export function LiveClassCard({ subject, teacher, attendees, schedule, status }: LiveClassCardProps) {
    return (
        <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
            <div className="flex items-start justify-between gap-2">
                <div>
                    <p className="text-sm font-semibold">{subject}</p>
                    <p className="mt-1 text-xs text-[var(--color-muted)]">{teacher}</p>
                </div>
                <span
                    className={`rounded-full px-2 py-1 text-[11px] font-semibold uppercase ${
                        status === 'live'
                            ? 'bg-rose-500/15 text-rose-700 dark:text-rose-300'
                            : status === 'upcoming'
                              ? 'bg-amber-500/15 text-amber-700 dark:text-amber-300'
                              : 'bg-slate-500/15 text-slate-700 dark:text-slate-300'
                    }`}
                >
                    {status}
                </span>
            </div>

            <div className="mt-3 grid grid-cols-2 gap-2 text-xs text-[var(--color-muted)]">
                <p className="inline-flex items-center gap-1">
                    <Clock3 size={12} /> {schedule}
                </p>
                <p className="inline-flex items-center gap-1">
                    <Users size={12} /> {attendees} attendees
                </p>
            </div>

            <div className="mt-3 flex items-center gap-2">
                <button type="button" className="btn-primary text-xs">
                    Quick Join
                </button>
                <button type="button" className="btn-ghost text-xs">
                    Resources
                </button>
                <button type="button" className="btn-ghost text-xs">
                    Chat
                </button>
            </div>
        </article>
    );
}

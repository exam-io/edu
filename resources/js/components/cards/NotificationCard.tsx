import { Bell } from 'lucide-react';

interface NotificationCardProps {
    title: string;
    message: string;
    time: string;
}

export function NotificationCard({ title, message, time }: NotificationCardProps) {
    return (
        <article className="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
            <div className="flex items-start gap-3">
                <span className="rounded-xl bg-[var(--color-surface-alt)] p-2 text-[var(--color-primary)]">
                    <Bell size={16} />
                </span>
                <div className="min-w-0 flex-1">
                    <p className="text-sm font-semibold">{title}</p>
                    <p className="mt-1 text-sm text-[var(--color-muted)]">{message}</p>
                    <p className="mt-2 font-mono text-xs text-[var(--color-muted)]">{time}</p>
                </div>
            </div>
        </article>
    );
}

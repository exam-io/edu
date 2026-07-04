import { Bell, CheckCheck } from 'lucide-react';
import { useState } from 'react';
import type { ShellNotification } from '@components/shell/types';

interface NotificationCenterProps {
    items: ShellNotification[];
}

export function NotificationCenter({ items }: NotificationCenterProps) {
    const [open, setOpen] = useState(false);

    return (
        <div className="relative">
            <button
                type="button"
                aria-label="Open notifications"
                onClick={() => setOpen((value) => !value)}
                className="relative rounded-[var(--radius-button)] border border-[var(--color-border)] bg-[var(--color-surface)] p-2 shadow-[var(--shadow-soft)] transition hover:border-[var(--color-primary)]"
            >
                <Bell size={16} />
                {items.length > 0 ? <span className="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-rose-500" /> : null}
            </button>

            {open ? (
                <section className="absolute right-0 top-12 z-40 w-[320px] overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[var(--shadow-elevated)]">
                    <header className="flex items-center justify-between border-b border-[var(--color-border)] px-4 py-3">
                        <p className="text-sm font-semibold">Notifications</p>
                        <button type="button" className="btn-ghost text-xs">
                            <CheckCheck size={14} />
                            Mark all
                        </button>
                    </header>
                    <div className="max-h-80 space-y-2 overflow-auto p-3">
                        {items.map((item) => (
                            <article key={item.id} className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                                <p className="text-sm font-medium">{item.title}</p>
                                <p className="mt-1 text-xs text-[var(--color-muted)]">{item.message}</p>
                                <p className="mt-2 font-mono text-[11px] text-[var(--color-muted)]">{item.time}</p>
                            </article>
                        ))}
                    </div>
                </section>
            ) : null}
        </div>
    );
}

import { ChevronDown, LogOut, UserCircle } from 'lucide-react';
import { useState } from 'react';

interface ProfileMenuProps {
    userName: string;
    userEmail: string;
    onLogout: () => void;
}

export function ProfileMenu({ userName, userEmail, onLogout }: ProfileMenuProps) {
    const [open, setOpen] = useState(false);

    return (
        <div className="relative">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="flex items-center gap-2 rounded-[var(--radius-button)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-left shadow-[var(--shadow-soft)] transition hover:border-[var(--color-primary)]"
            >
                <span className="hidden sm:block">
                    <p className="max-w-[160px] truncate text-xs font-semibold">{userName}</p>
                    <p className="max-w-[160px] truncate text-[11px] text-[var(--color-muted)]">{userEmail}</p>
                </span>
                <ChevronDown size={14} className="text-[var(--color-muted)]" />
            </button>

            {open ? (
                <section className="absolute right-0 top-12 z-40 w-56 overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[var(--shadow-elevated)]">
                    <button type="button" className="flex w-full items-center gap-2 px-3 py-2 text-sm hover:bg-[var(--color-surface-alt)]">
                        <UserCircle size={16} />
                        View profile
                    </button>
                    <button
                        type="button"
                        onClick={onLogout}
                        className="flex w-full items-center gap-2 px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                    >
                        <LogOut size={16} />
                        Logout
                    </button>
                </section>
            ) : null}
        </div>
    );
}

import { Search } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';
import type { ShellNavItem } from '@components/shell/types';

interface CommandPaletteProps {
    open: boolean;
    setOpen: (open: boolean) => void;
    navItems: ShellNavItem[];
    onNavigate: (path: string) => void;
}

export function CommandPalette({ open, setOpen, navItems, onNavigate }: CommandPaletteProps) {
    const [query, setQuery] = useState('');

    useEffect(() => {
        function onKeyDown(event: KeyboardEvent) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                setOpen(!open);
            }

            if (event.key === 'Escape') {
                setOpen(false);
            }
        }

        window.addEventListener('keydown', onKeyDown);
        return () => window.removeEventListener('keydown', onKeyDown);
    }, [open, setOpen]);

    const items = useMemo(() => {
        const needle = query.trim().toLowerCase();

        if (!needle) {
            return navItems.slice(0, 10);
        }

        return navItems.filter((item) => item.label.toLowerCase().includes(needle));
    }, [navItems, query]);

    if (!open) {
        return null;
    }

    return (
        <div className="fixed inset-0 z-[100] bg-black/45 p-4 backdrop-blur-sm" onClick={() => setOpen(false)}>
            <section
                className="mx-auto mt-14 w-full max-w-2xl overflow-hidden rounded-3xl border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[var(--shadow-elevated)]"
                onClick={(event) => event.stopPropagation()}
            >
                <header className="flex items-center gap-2 border-b border-[var(--color-border)] px-4 py-3">
                    <Search size={16} className="text-[var(--color-muted)]" />
                    <input
                        autoFocus
                        value={query}
                        onChange={(event) => setQuery(event.target.value)}
                        placeholder="Search pages, students, teachers, courses..."
                        className="w-full bg-transparent text-sm placeholder:text-[var(--color-muted)] focus:outline-none"
                    />
                </header>
                <div className="max-h-[60vh] overflow-auto p-2">
                    {items.map((item) => (
                        <button
                            key={item.path}
                            type="button"
                            className="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm transition hover:bg-[var(--color-surface-alt)]"
                            onClick={() => {
                                onNavigate(item.path);
                                setOpen(false);
                            }}
                        >
                            <span>{item.label}</span>
                            <span className="text-xs uppercase tracking-wide text-[var(--color-muted)]">{item.group}</span>
                        </button>
                    ))}
                </div>
            </section>
        </div>
    );
}

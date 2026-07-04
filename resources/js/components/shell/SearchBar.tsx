import { Search } from 'lucide-react';

interface SearchBarProps {
    value: string;
    onChange: (value: string) => void;
    onFocus?: () => void;
}

export function SearchBar({ value, onChange, onFocus }: SearchBarProps) {
    return (
        <label className="hidden min-w-[280px] items-center gap-2 rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm shadow-[var(--shadow-soft)] md:flex">
            <Search size={16} className="text-[var(--color-muted)]" />
            <input
                value={value}
                onChange={(event) => onChange(event.target.value)}
                onFocus={onFocus}
                placeholder="Search students, teachers, courses..."
                className="w-full bg-transparent text-sm text-[var(--color-text)] placeholder:text-[var(--color-muted)] focus:outline-none"
            />
            <span className="rounded-md border border-[var(--color-border)] px-1.5 py-0.5 font-mono text-xs text-[var(--color-muted)]">Ctrl K</span>
        </label>
    );
}

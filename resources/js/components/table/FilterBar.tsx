import type { ReactNode } from 'react';

interface FilterBarProps {
    searchSlot?: ReactNode;
    filtersSlot?: ReactNode;
    actionsSlot?: ReactNode;
}

export function FilterBar({ searchSlot, filtersSlot, actionsSlot }: FilterBarProps) {
    return (
        <div className="flex flex-col gap-3 rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] p-3 shadow-[var(--shadow-soft)] md:flex-row md:items-center md:justify-between">
            <div className="flex flex-1 items-center gap-3">{searchSlot}</div>
            <div className="flex flex-wrap items-center gap-2">{filtersSlot}</div>
            <div className="flex items-center gap-2">{actionsSlot}</div>
        </div>
    );
}

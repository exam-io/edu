import { useEffect, useMemo, useState } from 'react';

export function useDraftAutosave<T extends object>(key: string, value: T) {
    const [lastSavedAt, setLastSavedAt] = useState<Date | null>(null);

    const storageKey = useMemo(() => `eduos:draft:${key}`, [key]);

    useEffect(() => {
        const id = window.setTimeout(() => {
            localStorage.setItem(storageKey, JSON.stringify(value));
            setLastSavedAt(new Date());
        }, 500);

        return () => window.clearTimeout(id);
    }, [storageKey, value]);

    function hydrate(defaultValue: T): T {
        try {
            const raw = localStorage.getItem(storageKey);
            if (!raw) {
                return defaultValue;
            }

            return { ...defaultValue, ...(JSON.parse(raw) as T) };
        } catch {
            return defaultValue;
        }
    }

    function clearDraft() {
        localStorage.removeItem(storageKey);
    }

    return { lastSavedAt, hydrate, clearDraft };
}

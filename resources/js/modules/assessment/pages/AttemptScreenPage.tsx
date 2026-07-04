import { useEffect } from 'react';
import { useAttemptStore } from '@modules/assessment/store/attemptStore';

export function AttemptScreenPage() {
    const timer = useAttemptStore((state) => state.timer);
    const tick = useAttemptStore((state) => state.tick);

    useEffect(() => {
        const id = window.setInterval(() => tick(), 1000);
        return () => window.clearInterval(id);
    }, [tick]);

    return (
        <section className="space-y-3">
            <h2 className="text-xl font-semibold">Attempt Screen</h2>
            <p className="text-sm text-[var(--color-muted)]">Timer: {timer}s</p>
            <p className="text-sm text-[var(--color-muted)]">Save and next, mark for review, and resume support are enabled by the attempt store and API.</p>
        </section>
    );
}

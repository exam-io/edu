import { CalendarDays } from 'lucide-react';

interface EventItem {
    id: string;
    title: string;
    time: string;
    type: 'class' | 'exam' | 'assignment' | 'meeting';
}

interface CalendarWidgetProps {
    events: EventItem[];
}

const typeColors: Record<EventItem['type'], string> = {
    class: 'bg-blue-500',
    exam: 'bg-rose-500',
    assignment: 'bg-amber-500',
    meeting: 'bg-emerald-500',
};

export function CalendarWidget({ events }: CalendarWidgetProps) {
    return (
        <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <header className="mb-4 flex items-center gap-2">
                <CalendarDays size={16} className="text-[var(--color-primary)]" />
                <h3 className="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">Calendar</h3>
            </header>

            <div className="space-y-3">
                {events.map((event) => (
                    <article key={event.id} className="flex items-center gap-3 rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                        <span className={`h-2.5 w-2.5 rounded-full ${typeColors[event.type]}`} />
                        <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-medium">{event.title}</p>
                            <p className="font-mono text-xs text-[var(--color-muted)]">{event.time}</p>
                        </div>
                    </article>
                ))}
            </div>
        </section>
    );
}

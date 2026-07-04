import { CalendarDays, List, PanelTop, Rows3 } from 'lucide-react';

export type CalendarView = 'month' | 'week' | 'day' | 'agenda';

interface CalendarEvent {
    id: string;
    title: string;
    time: string;
    type: 'class' | 'live' | 'exam' | 'assignment' | 'holiday' | 'meeting';
}

interface PremiumCalendarProps {
    view: CalendarView;
    onViewChange: (view: CalendarView) => void;
    events: CalendarEvent[];
}

const colorMap: Record<CalendarEvent['type'], string> = {
    class: 'bg-blue-500/15 text-blue-700 dark:text-blue-300',
    live: 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300',
    exam: 'bg-rose-500/15 text-rose-700 dark:text-rose-300',
    assignment: 'bg-amber-500/15 text-amber-700 dark:text-amber-300',
    holiday: 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-300',
    meeting: 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-300',
};

export function PremiumCalendar({ view, onViewChange, events }: PremiumCalendarProps) {
    return (
        <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
            <header className="mb-4 flex flex-wrap items-center justify-between gap-2">
                <div>
                    <h2 className="text-base font-semibold">Institute Calendar</h2>
                    <p className="text-xs text-[var(--color-muted)]">Month, week, day, and agenda views</p>
                </div>
                <div className="flex items-center gap-1">
                    <button type="button" className={`btn-ghost ${view === 'month' ? 'border-[var(--color-primary)]' : ''}`} onClick={() => onViewChange('month')}>
                        <CalendarDays size={14} /> Month
                    </button>
                    <button type="button" className={`btn-ghost ${view === 'week' ? 'border-[var(--color-primary)]' : ''}`} onClick={() => onViewChange('week')}>
                        <PanelTop size={14} /> Week
                    </button>
                    <button type="button" className={`btn-ghost ${view === 'day' ? 'border-[var(--color-primary)]' : ''}`} onClick={() => onViewChange('day')}>
                        <Rows3 size={14} /> Day
                    </button>
                    <button type="button" className={`btn-ghost ${view === 'agenda' ? 'border-[var(--color-primary)]' : ''}`} onClick={() => onViewChange('agenda')}>
                        <List size={14} /> Agenda
                    </button>
                </div>
            </header>

            <div className="grid gap-3 md:grid-cols-2">
                {events.map((event) => (
                    <article key={event.id} className="flex items-center justify-between rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] px-3 py-2">
                        <div>
                            <p className="text-sm font-medium">{event.title}</p>
                            <p className="font-mono text-xs text-[var(--color-muted)]">{event.time}</p>
                        </div>
                        <span className={`rounded-full px-2 py-1 text-xs font-medium capitalize ${colorMap[event.type]}`}>{event.type}</span>
                    </article>
                ))}
            </div>
        </section>
    );
}

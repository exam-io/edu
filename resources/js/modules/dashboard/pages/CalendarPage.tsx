import { useEffect, useMemo, useState } from 'react';
import type { CalendarView } from '@components/widgets/PremiumCalendar';
import { PremiumCalendar } from '@components/widgets/PremiumCalendar';
import { useCalendarStore } from '@modules/calendar/store/calendarStore';

export function CalendarPage() {
    const [view, setView] = useState<CalendarView>('month');
    const events = useCalendarStore((state) => state.events);
    const loading = useCalendarStore((state) => state.loading);
    const error = useCalendarStore((state) => state.error);
    const fetchEvents = useCalendarStore((state) => state.fetchEvents);

    useEffect(() => {
        void fetchEvents();
    }, [fetchEvents]);

    const uiEvents: Array<{ id: string; title: string; time: string; type: 'live' | 'class' | 'exam' | 'assignment' | 'holiday' | 'meeting' }> = useMemo(
        () =>
            events.map((event) => ({
                id: String(event.id),
                title: event.title,
                time: new Date(event.start_at).toLocaleString(),
                type: event.event_type === 'live_class' ? 'live' : 'class',
            })),
        [events],
    );

    return (
        <section className="space-y-4">
            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading calendar events...</p> : null}
            {error ? <p className="text-sm text-rose-600">{error}</p> : null}
            <PremiumCalendar
                view={view}
                onViewChange={setView}
                events={uiEvents}
            />
        </section>
    );
}

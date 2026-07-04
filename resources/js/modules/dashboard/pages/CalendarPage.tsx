import { useState } from 'react';
import type { CalendarView } from '@components/widgets/PremiumCalendar';
import { PremiumCalendar } from '@components/widgets/PremiumCalendar';

export function CalendarPage() {
    const [view, setView] = useState<CalendarView>('month');

    return (
        <section className="space-y-4">
            <PremiumCalendar
                view={view}
                onViewChange={setView}
                events={[
                    { id: 'ev-1', title: 'Class 10 Physics Live', time: 'Mon 10:00 AM', type: 'live' },
                    { id: 'ev-2', title: 'Algebra Midterm', time: 'Tue 01:00 PM', type: 'exam' },
                    { id: 'ev-3', title: 'Chemistry Assignment', time: 'Wed 11:30 AM', type: 'assignment' },
                    { id: 'ev-4', title: 'Faculty Meeting', time: 'Thu 03:00 PM', type: 'meeting' },
                    { id: 'ev-5', title: 'Founders Day Holiday', time: 'Fri All Day', type: 'holiday' },
                    { id: 'ev-6', title: 'Grade 8 Math', time: 'Sat 09:30 AM', type: 'class' },
                ]}
            />
        </section>
    );
}

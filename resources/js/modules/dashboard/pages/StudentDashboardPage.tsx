import { BookOpen, CalendarCheck2, CheckCircle2, Trophy } from 'lucide-react';
import { ChartCard } from '@components/cards/ChartCard';
import { ProgressCard } from '@components/cards/ProgressCard';
import { ResultCard } from '@components/cards/ResultCard';
import { StatCard } from '@components/cards/StatCard';
import { CalendarWidget } from '@components/widgets/CalendarWidget';

export function StudentDashboardPage() {
    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Student Dashboard</h2>
                <p className="text-sm text-[var(--color-muted)]">Follow your classes, assignments, exam prep, and progress milestones.</p>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Courses" value="8" trend="Current semester" icon={BookOpen} sparkline={[6, 6, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8]} />
                <StatCard label="Assignments Due" value="3" trend="2 due this week" icon={CalendarCheck2} sparkline={[7, 6, 6, 5, 5, 4, 4, 3, 4, 3, 3, 3]} />
                <StatCard label="Completed Tasks" value="27" trend="+6 this week" icon={CheckCircle2} sparkline={[12, 13, 15, 16, 18, 19, 20, 22, 23, 25, 26, 27]} />
                <StatCard label="Class Rank" value="#9" trend="Up by 2" icon={Trophy} sparkline={[16, 15, 14, 14, 13, 12, 11, 11, 10, 10, 9, 9]} />
            </div>

            <div className="grid gap-4 xl:grid-cols-[1fr_1fr]">
                <ChartCard title="Upcoming" subtitle="Stay ahead of deadlines">
                    <CalendarWidget
                        events={[
                            { id: 's-1', title: 'Math Live Class', time: '09:30 AM', type: 'class' },
                            { id: 's-2', title: 'Biology Quiz', time: '01:00 PM', type: 'exam' },
                            { id: 's-3', title: 'Worksheet Submission', time: '05:00 PM', type: 'assignment' },
                        ]}
                    />
                </ChartCard>

                <div className="space-y-3">
                    <ProgressCard title="Syllabus Completion" value={61} subtitle="Target 70% this month" />
                    <ResultCard title="Latest Assessment" score={86} rank={9} status="good" />
                </div>
            </div>
        </section>
    );
}

import { BookOpen, CheckSquare, Clock3, Presentation, Users } from 'lucide-react';
import { ChartCard } from '@components/cards/ChartCard';
import { LiveClassCard } from '@components/cards/LiveClassCard';
import { ProgressCard } from '@components/cards/ProgressCard';
import { StatCard } from '@components/cards/StatCard';

export function TeacherDashboardPage() {
    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Teacher Dashboard</h2>
                <p className="text-sm text-[var(--color-muted)]">Track classes, assessments, grading queue, and learner progress in one view.</p>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Today Classes" value="5" trend="+1 vs yesterday" icon={Presentation} sparkline={[3, 4, 4, 3, 4, 5, 4, 5, 5, 4, 5, 5]} />
                <StatCard label="Assignments Pending" value="32" trend="-8 cleared" icon={CheckSquare} sparkline={[38, 37, 36, 34, 35, 33, 32, 31, 30, 33, 32, 32]} />
                <StatCard label="Attendance" value="92%" trend="+2.3%" icon={Users} sparkline={[84, 85, 86, 88, 87, 89, 90, 91, 91, 92, 92, 92]} />
                <StatCard label="Course Coverage" value="74%" trend="On schedule" icon={BookOpen} sparkline={[52, 55, 58, 60, 63, 66, 67, 69, 71, 72, 73, 74]} />
            </div>

            <div className="grid gap-4 xl:grid-cols-[1.1fr_1fr]">
                <ChartCard title="Live Sessions" subtitle="Join instantly and monitor attendance">
                    <div className="grid gap-3 md:grid-cols-2">
                        <LiveClassCard subject="Grade 10 Algebra" teacher="You" attendees={41} schedule="09:30 - 10:15" status="live" />
                        <LiveClassCard subject="Grade 9 Physics" teacher="You" attendees={34} schedule="11:00 - 11:45" status="upcoming" />
                    </div>
                </ChartCard>

                <div className="space-y-3">
                    <ProgressCard title="Grading Completion" value={68} subtitle="Target 100% by Friday" />
                    <ProgressCard title="Weekly Lesson Plan" value={81} subtitle="On track" />
                    <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                        <p className="inline-flex items-center gap-2 text-sm font-medium">
                            <Clock3 size={14} /> Next Reminder
                        </p>
                        <p className="mt-2 text-sm text-[var(--color-muted)]">Publish Grade 9 quiz by 4:00 PM.</p>
                    </article>
                </div>
            </div>
        </section>
    );
}

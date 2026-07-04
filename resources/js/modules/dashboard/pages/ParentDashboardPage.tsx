import { BellRing, BookOpen, CircleCheckBig, UserRound } from 'lucide-react';
import { NotificationCard } from '@components/cards/NotificationCard';
import { ProgressCard } from '@components/cards/ProgressCard';
import { StatCard } from '@components/cards/StatCard';

export function ParentDashboardPage() {
    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Parent Dashboard</h2>
                <p className="text-sm text-[var(--color-muted)]">Monitor your child performance, attendance, assignments, and institute updates.</p>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Child Attendance" value="94%" trend="+1.2%" icon={UserRound} sparkline={[88, 89, 90, 90, 91, 92, 92, 93, 93, 94, 94, 94]} />
                <StatCard label="Assignments Done" value="18" trend="+4 this week" icon={CircleCheckBig} sparkline={[10, 11, 11, 12, 13, 14, 15, 16, 17, 17, 18, 18]} />
                <StatCard label="Subjects Tracked" value="7" trend="Current term" icon={BookOpen} sparkline={[6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7]} />
                <StatCard label="Alerts" value="2" trend="Needs attention" trendUp={false} icon={BellRing} sparkline={[1, 1, 2, 2, 3, 2, 2, 1, 2, 2, 2, 2]} />
            </div>

            <div className="grid gap-4 xl:grid-cols-[1fr_1fr]">
                <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)]">
                    <h3 className="text-base font-semibold">Recent Notifications</h3>
                    <div className="mt-3 space-y-2">
                        <NotificationCard title="Homework Uploaded" message="Science worksheet uploaded for Class 8." time="1h" />
                        <NotificationCard title="PTM Scheduled" message="Parent-teacher meeting on Friday at 3:00 PM." time="Today" />
                    </div>
                </article>

                <div className="space-y-3">
                    <ProgressCard title="Monthly Attendance Goal" value={94} subtitle="Excellent consistency" />
                    <ProgressCard title="Assignment Completion Goal" value={78} subtitle="Need 2 more this week" />
                </div>
            </div>
        </section>
    );
}

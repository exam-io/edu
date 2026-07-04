import {
    Banknote,
    BookOpen,
    CalendarClock,
    ClipboardList,
    FileText,
    Sparkles,
    Users,
} from 'lucide-react';
import { Link } from 'react-router-dom';
import { ActivityTimeline } from '@components/cards/ActivityTimeline';
import { ChartCard } from '@components/cards/ChartCard';
import { NotificationCard } from '@components/cards/NotificationCard';
import { ProgressCard } from '@components/cards/ProgressCard';
import { ResultCard } from '@components/cards/ResultCard';
import { StatCard } from '@components/cards/StatCard';
import { AIUploadCard } from '@components/forms/AIUploadCard';
import { FileDropzone } from '@components/forms/FileDropzone';
import { EmptyState } from '@components/states/EmptyState';
import { DataTable } from '@components/table/DataTable';
import { FilterBar } from '@components/table/FilterBar';
import { CalendarWidget } from '@components/widgets/CalendarWidget';

export function AcademicHomePage() {
    const studentsRows = [
        { name: 'Aarav Shah', className: 'Grade 9', status: 'Active', score: '91%' },
        { name: 'Anika Verma', className: 'Grade 10', status: 'At Risk', score: '68%' },
        { name: 'Kabir Gupta', className: 'Grade 8', status: 'Active', score: '88%' },
        { name: 'Riya Iyer', className: 'Grade 11', status: 'Excellent', score: '96%' },
    ];

    return (
        <div className="space-y-6">
            <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
                <StatCard label="Students" value="12,860" trend="+8.2% this month" icon={Users} sparkline={[10, 14, 12, 15, 19, 21, 23, 25, 24, 29, 31, 33]} />
                <StatCard label="Teachers" value="824" trend="+2.1% this month" icon={Users} sparkline={[6, 7, 7, 8, 8, 9, 9, 10, 10, 11, 12, 12]} />
                <StatCard label="Courses" value="412" trend="+5.6% this month" icon={BookOpen} sparkline={[4, 5, 4, 6, 7, 8, 8, 9, 11, 10, 12, 14]} />
                <StatCard label="Exams" value="73" trend="+11.4% this week" icon={ClipboardList} sparkline={[4, 3, 5, 6, 7, 8, 10, 11, 13, 12, 15, 17]} />
                <StatCard label="Revenue" value="$92,440" trend="+14.8% QoQ" icon={Banknote} sparkline={[8, 10, 12, 11, 14, 15, 16, 18, 17, 20, 22, 26]} />
                <StatCard label="Live Classes" value="28" trend="-1.3% vs yesterday" trendUp={false} icon={CalendarClock} sparkline={[10, 12, 9, 11, 10, 8, 11, 9, 8, 7, 6, 5]} />
            </section>

            <section className="grid gap-4 xl:grid-cols-[1.4fr_1fr]">
                <ChartCard title="Today Schedule" subtitle="Upcoming classes, exams, and sessions">
                    <ActivityTimeline
                        items={[
                            { id: 'a-1', title: 'Grade 10 Math', detail: 'Live class with 42 students', time: '09:00 - 09:45' },
                            { id: 'a-2', title: 'Physics Lab Exam', detail: 'Lab 2, supervised by Dr. Mehta', time: '11:00 - 12:00' },
                            { id: 'a-3', title: 'Parent-Teacher Meet', detail: 'Section B discussion slot', time: '16:30 - 17:00' },
                        ]}
                    />
                </ChartCard>

                <div className="space-y-4">
                    <ProgressCard title="Attendance Health" value={88} subtitle="Institute average" />
                    <ProgressCard title="Assignment Completion" value={72} subtitle="Due by Friday" />
                    <ResultCard title="Top Performing Cohort" score={94} rank={1} status="excellent" />
                </div>
            </section>

            <section className="grid gap-4 lg:grid-cols-3">
                <CalendarWidget
                    events={[
                        { id: 'e-1', title: 'Chemistry Live Session', time: '10:30 AM', type: 'class' },
                        { id: 'e-2', title: 'Midterm: Algebra', time: '01:00 PM', type: 'exam' },
                        { id: 'e-3', title: 'Worksheet Submission', time: '04:30 PM', type: 'assignment' },
                    ]}
                />

                <div className="space-y-3">
                    <NotificationCard title="Pending approvals" message="8 teacher assignment requests need review." time="Now" />
                    <NotificationCard title="AI suggestion" message="Generate a revision sheet for Grade 8 Science." time="5m" />
                    <NotificationCard title="New enrollment" message="24 new students onboarded this week." time="1h" />
                </div>

                <AIUploadCard
                    title="AI Studio Quick Generate"
                    description="Upload notes and generate summaries, flashcards, worksheets, or assessments instantly."
                    dropzone={<FileDropzone title="Upload PDF, Notes, or Book" accept=".pdf,.doc,.docx,.txt" />}
                />
            </section>

            <section className="space-y-3">
                <FilterBar
                    searchSlot={<input className="w-full rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface-alt)] px-3 py-2 text-sm" placeholder="Search students by name, class, or roll no." />}
                    filtersSlot={
                        <>
                            <select className="rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm">
                                <option>All classes</option>
                                <option>Grade 8</option>
                                <option>Grade 9</option>
                                <option>Grade 10</option>
                            </select>
                            <select className="rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm">
                                <option>All statuses</option>
                                <option>Active</option>
                                <option>Excellent</option>
                                <option>At Risk</option>
                            </select>
                        </>
                    }
                    actionsSlot={
                        <>
                            <button type="button" className="btn-ghost">Saved view</button>
                            <button type="button" className="btn-primary">Bulk action</button>
                        </>
                    }
                />

                <DataTable
                    title="Student Progress Snapshot"
                    rows={studentsRows}
                    columns={[
                        {
                            key: 'name',
                            header: 'Student',
                            cell: (row) => (
                                <div className="flex items-center gap-3">
                                    <span className="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[var(--color-surface-alt)] text-xs font-semibold text-[var(--color-primary)]">
                                        {row.name
                                            .split(' ')
                                            .map((part) => part[0])
                                            .join('')}
                                    </span>
                                    <p className="font-medium">{row.name}</p>
                                </div>
                            ),
                        },
                        { key: 'className', header: 'Class', cell: (row) => row.className },
                        {
                            key: 'status',
                            header: 'Status',
                            cell: (row) => (
                                <span className="rounded-full border border-[var(--color-border)] bg-[var(--color-surface-alt)] px-2 py-1 text-xs">
                                    {row.status}
                                </span>
                            ),
                        },
                        { key: 'score', header: 'Avg Score', cell: (row) => <span className="font-mono font-semibold">{row.score}</span> },
                        {
                            key: 'actions',
                            header: 'Action',
                            cell: () => (
                                <button type="button" className="btn-ghost text-xs">
                                    View Profile
                                </button>
                            ),
                        },
                    ]}
                    emptyState={
                        <EmptyState
                            title="No learners found"
                            description="Try changing filters or create a new student profile to get started."
                            illustration={<Sparkles size={24} />}
                            primaryAction={<Link to="/students/create" className="btn-primary">Create Student</Link>}
                            secondaryAction={<button type="button" className="btn-ghost">Clear Filters</button>}
                        />
                    }
                />
            </section>

            <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Link to="/academic/departments" className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)] transition hover:-translate-y-0.5 hover:shadow-[var(--shadow-elevated)]">
                    <p className="text-xs uppercase tracking-[0.16em] text-[var(--color-muted)]">Structure</p>
                    <p className="mt-2 text-base font-semibold">Manage Departments</p>
                </Link>
                <Link to="/assessments/create" className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)] transition hover:-translate-y-0.5 hover:shadow-[var(--shadow-elevated)]">
                    <p className="text-xs uppercase tracking-[0.16em] text-[var(--color-muted)]">Assessment</p>
                    <p className="mt-2 text-base font-semibold">Build Assessment</p>
                </Link>
                <Link to="/ai" className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 shadow-[var(--shadow-soft)] transition hover:-translate-y-0.5 hover:shadow-[var(--shadow-elevated)]">
                    <p className="text-xs uppercase tracking-[0.16em] text-[var(--color-muted)]">AI</p>
                    <p className="mt-2 text-base font-semibold">Open AI Studio</p>
                </Link>
            </section>

            <section className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
                <h2 className="text-base font-semibold">Generation History</h2>
                <p className="mt-1 text-sm text-[var(--color-muted)]">Track AI jobs for notes, summaries, flash cards, and worksheets.</p>
                <div className="mt-4 grid gap-3 md:grid-cols-2">
                    <article className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                        <p className="text-sm font-semibold">Chapter 4 Flashcards</p>
                        <p className="mt-1 text-xs text-[var(--color-muted)]">Completed in 43s</p>
                    </article>
                    <article className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                        <p className="text-sm font-semibold">Physics Worksheet (MCQ)</p>
                        <p className="mt-1 text-xs text-[var(--color-muted)]">In queue</p>
                    </article>
                </div>

                <div className="mt-4 h-2 overflow-hidden rounded-full bg-[var(--color-surface-alt)]">
                    <div className="h-full w-2/3 rounded-full bg-gradient-to-r from-[var(--color-secondary)] to-[var(--color-primary)]" />
                </div>
                <p className="mt-2 flex items-center gap-2 text-xs text-[var(--color-muted)]">
                    <FileText size={12} />
                    2/3 jobs finished
                </p>
            </section>
        </div>
    );
}

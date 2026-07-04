export function ResultDashboardPage() {
    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Result Dashboard</h2>
            <div className="grid gap-3 md:grid-cols-3">
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Average Score</p>
                    <p className="mt-1 text-2xl font-semibold">--</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Pass Rate</p>
                    <p className="mt-1 text-2xl font-semibold">--</p>
                </article>
                <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <p className="text-xs text-[var(--color-muted)]">Submissions</p>
                    <p className="mt-1 text-2xl font-semibold">--</p>
                </article>
            </div>
        </section>
    );
}

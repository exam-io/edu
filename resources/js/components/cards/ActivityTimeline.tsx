interface ActivityItem {
    id: string;
    title: string;
    detail: string;
    time: string;
}

interface ActivityTimelineProps {
    items: ActivityItem[];
}

export function ActivityTimeline({ items }: ActivityTimelineProps) {
    return (
        <ol className="space-y-4">
            {items.map((item) => (
                <li key={item.id} className="relative pl-6">
                    <span className="absolute left-0 top-1.5 h-2.5 w-2.5 rounded-full bg-[var(--color-primary)]" />
                    <div className="rounded-xl border border-[var(--color-border)] bg-[var(--color-surface-alt)] p-3">
                        <p className="text-sm font-semibold">{item.title}</p>
                        <p className="mt-1 text-sm text-[var(--color-muted)]">{item.detail}</p>
                        <p className="mt-2 font-mono text-xs text-[var(--color-muted)]">{item.time}</p>
                    </div>
                </li>
            ))}
        </ol>
    );
}

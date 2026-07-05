import { useEffect } from 'react';
import { HardDriveUpload } from 'lucide-react';
import { StatCard } from '@components/cards/StatCard';
import { useOperationsStore } from '@modules/operations/store/operationsStore';

export function OperationsCenterPage() {
    const latestBackup = useOperationsStore((state) => state.latestBackup);
    const queueLogs = useOperationsStore((state) => state.queueLogs);
    const loading = useOperationsStore((state) => state.loading);
    const error = useOperationsStore((state) => state.error);
    const initialize = useOperationsStore((state) => state.initialize);
    const runBackup = useOperationsStore((state) => state.runBackup);

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <header className="flex items-center justify-between">
                <div>
                    <h2 className="text-xl font-semibold">Operations Center</h2>
                    <p className="text-sm text-[var(--color-muted)]">Backup pipeline orchestration and queue operations for enterprise reliability.</p>
                </div>
                <button
                    className="rounded-md border border-[var(--color-border)] px-3 py-2 text-sm"
                    onClick={() => {
                        void runBackup();
                    }}
                    type="button"
                >
                    Trigger Backup
                </button>
            </header>

            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <StatCard label="Latest Backup Status" value={latestBackup?.status ?? 'none'} icon={HardDriveUpload} />
                <StatCard label="Queue Operations" value={String(queueLogs.length)} icon={HardDriveUpload} />
            </div>

            {loading ? <p className="text-sm text-[var(--color-muted)]">Loading operations telemetry...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}

            <div className="rounded-xl border border-[var(--color-border)] bg-white/60 p-4">
                <h3 className="mb-2 text-sm font-semibold">Queue Operation Journal</h3>
                <ul className="space-y-2 text-sm">
                    {queueLogs.map((item) => (
                        <li className="rounded-md border border-[var(--color-border)] px-3 py-2" key={item.id}>
                            <span className="font-medium">{item.operation}</span>
                            <span className="ml-2 text-[var(--color-muted)]">{item.status}</span>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}

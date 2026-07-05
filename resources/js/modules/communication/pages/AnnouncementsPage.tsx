import { useEffect } from 'react';
import { useCommunicationStore } from '@modules/communication/store/communicationStore';

export function AnnouncementsPage() {
    const announcements = useCommunicationStore((state) => state.announcements);
    const history = useCommunicationStore((state) => state.history);
    const loading = useCommunicationStore((state) => state.loading);
    const error = useCommunicationStore((state) => state.error);
    const fetchData = useCommunicationStore((state) => state.fetchData);

    useEffect(() => {
        void fetchData();
    }, [fetchData]);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Communication Center</h2>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-4 lg:grid-cols-2">
                <div className="space-y-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <h3 className="font-medium">Announcements</h3>
                    {announcements.map((item) => (
                        <p key={item.id} className="text-sm text-[var(--color-muted)]">{item.title} ({item.status})</p>
                    ))}
                </div>
                <div className="space-y-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                    <h3 className="font-medium">Communication History</h3>
                    {history.map((item) => (
                        <p key={item.id} className="text-sm text-[var(--color-muted)]">{item.source_type} | {item.channel} | {item.status}</p>
                    ))}
                </div>
            </div>
        </section>
    );
}

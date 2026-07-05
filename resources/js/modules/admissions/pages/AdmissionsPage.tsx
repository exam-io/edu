import { useEffect } from 'react';
import { useAdmissionsStore } from '@modules/admissions/store/admissionsStore';

export function AdmissionsPage() {
    const applications = useAdmissionsStore((state) => state.applications);
    const loading = useAdmissionsStore((state) => state.loading);
    const error = useAdmissionsStore((state) => state.error);
    const fetchApplications = useAdmissionsStore((state) => state.fetchApplications);

    useEffect(() => {
        void fetchApplications();
    }, [fetchApplications]);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Admissions Workflow</h2>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {applications.map((application) => (
                    <div key={application.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="font-medium">{application.full_name}</p>
                        <p className="text-xs text-[var(--color-muted)]">{application.email} | {application.program ?? 'General'} | {application.status}</p>
                    </div>
                ))}
            </div>
        </section>
    );
}

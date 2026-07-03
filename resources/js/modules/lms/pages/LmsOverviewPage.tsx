import { useEffect } from 'react';
import { useLmsStore } from '@modules/lms/store/lmsStore';

export function LmsOverviewPage() {
    const { enrollments, progress, loading, error, initialize } = useLmsStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">LMS Overview</h1>
            {loading ? <p>Loading LMS data...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4 space-y-2">
                <p className="text-sm text-gray-500">Enrollments: {enrollments.length}</p>
                <p className="text-sm text-gray-500">Progress records: {progress.length}</p>
            </div>
        </section>
    );
}

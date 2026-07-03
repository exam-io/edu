import { useEffect } from 'react';
import { useCourseStore } from '@modules/course/store/courseStore';

export function CoursesPage() {
    const { items, loading, error, initialize } = useCourseStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">Courses</h1>
            {loading ? <p>Loading courses...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total courses: {items.length}</p>
                <ul className="mt-3 space-y-2">
                    {items.map((item) => (
                        <li key={item.id} className="rounded bg-gray-50 px-3 py-2">
                            <p className="font-medium">{item.title}</p>
                            <p className="text-xs text-gray-500">{item.code} • {item.status}</p>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}

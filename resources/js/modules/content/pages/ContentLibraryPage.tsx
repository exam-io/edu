import { useEffect } from 'react';
import { useContentStore } from '@modules/content/store/contentStore';

export function ContentLibraryPage() {
    const { items, loading, error, initialize } = useContentStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">Content Library</h1>
            {loading ? <p>Loading content...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total content items: {items.length}</p>
            </div>
        </section>
    );
}

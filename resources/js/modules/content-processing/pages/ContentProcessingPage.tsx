import { useEffect } from 'react';
import { useContentProcessingStore } from '@modules/content-processing/store/contentProcessingStore';

export function ContentProcessingPage() {
    const { sources, loading, error, initialize } = useContentProcessingStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">Content Processing</h1>
            {loading ? <p>Loading content processing sources...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total processing sources: {sources.length}</p>
            </div>
        </section>
    );
}

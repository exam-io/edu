import { useEffect } from 'react';
import { useMediaStore } from '@modules/media/store/mediaStore';

export function MediaLibraryPage() {
    const { items, loading, error, initialize } = useMediaStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">Media Library</h1>
            {loading ? <p>Loading media assets...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total assets: {items.length}</p>
            </div>
        </section>
    );
}

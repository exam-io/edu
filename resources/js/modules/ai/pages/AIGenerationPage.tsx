import { useEffect } from 'react';
import { useAIStore } from '@modules/ai/store/aiStore';

export function AIGenerationPage() {
    const { requests, loading, error, initialize } = useAIStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">AI Content Engine</h1>
            {loading ? <p>Loading generation requests...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total AI requests: {requests.length}</p>
            </div>
        </section>
    );
}

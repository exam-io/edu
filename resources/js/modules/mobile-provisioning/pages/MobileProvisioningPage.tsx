import { useEffect } from 'react';
import { useMobileProvisioningStore } from '@modules/mobile-provisioning/store/mobileProvisioningStore';

export function MobileProvisioningPage() {
    const { config, requests, loading, error, initialize, publish } = useMobileProvisioningStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Mobile Provisioning</h1>
                <p className="text-sm text-gray-500">Publish mobile runtime config and track build requests.</p>
            </header>
            <div className="rounded border p-4">
                <p className="text-sm">Current version: <span className="font-semibold">{config?.version ?? 0}</span></p>
                <button className="mt-3 rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} onClick={() => { void publish(); }} type="button">
                    {loading ? 'Publishing...' : 'Publish Config'}
                </button>
            </div>
            <div className="rounded border p-4">
                <h2 className="font-semibold">Build Requests</h2>
                <ul className="mt-2 grid gap-2">
                    {requests.map((request) => (
                        <li className="rounded border p-2" key={request.id}>
                            {request.platform} - {request.status}
                        </li>
                    ))}
                </ul>
            </div>
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}

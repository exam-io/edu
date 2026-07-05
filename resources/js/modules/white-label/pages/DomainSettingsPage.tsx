import { FormEvent, useEffect, useState } from 'react';
import { useWhiteLabelStore } from '@modules/white-label/store/whiteLabelStore';

export function DomainSettingsPage() {
    const { domains, loading, error, initialize, addDomain } = useWhiteLabelStore();
    const [host, setHost] = useState('');

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        await addDomain(host);
        setHost('');
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Custom Domains</h1>
                <p className="text-sm text-gray-500">Map and verify white-label domains for this tenant.</p>
            </header>
            <form className="flex gap-2 max-w-xl" onSubmit={onSubmit}>
                <input className="flex-1 rounded border p-2" onChange={(event) => setHost(event.target.value)} placeholder="school.example.com" value={host} />
                <button className="rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} type="submit">Add</button>
            </form>
            <ul className="grid gap-2 max-w-2xl">
                {domains.map((domain) => (
                    <li className="rounded border p-3" key={domain.id}>
                        <p className="font-medium">{domain.host}</p>
                        <p className="text-xs text-gray-500">{domain.status}{domain.is_primary ? ' • primary' : ''}</p>
                    </li>
                ))}
            </ul>
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
        </section>
    );
}

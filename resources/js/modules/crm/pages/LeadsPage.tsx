import { FormEvent, useEffect, useState } from 'react';
import { useCRMStore } from '@modules/crm/store/crmStore';

export function LeadsPage() {
    const leads = useCRMStore((state) => state.leads);
    const loading = useCRMStore((state) => state.loading);
    const error = useCRMStore((state) => state.error);
    const fetchLeads = useCRMStore((state) => state.fetchLeads);
    const createLead = useCRMStore((state) => state.createLead);

    const [name, setName] = useState('');
    const [email, setEmail] = useState('');

    useEffect(() => {
        void fetchLeads();
    }, [fetchLeads]);

    const handleSubmit = async (event: FormEvent) => {
        event.preventDefault();
        await createLead({ first_name: name, email, source: 'manual' });
        setName('');
        setEmail('');
    };

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">CRM Leads</h2>
            <form className="flex flex-col gap-2 rounded-lg border border-[var(--color-border)] p-4 md:flex-row" onSubmit={handleSubmit}>
                <input className="rounded border border-[var(--color-border)] px-3 py-2" placeholder="Lead name" value={name} onChange={(event) => setName(event.target.value)} required />
                <input className="rounded border border-[var(--color-border)] px-3 py-2" placeholder="Email" type="email" value={email} onChange={(event) => setEmail(event.target.value)} required />
                <button className="rounded bg-[var(--color-primary)] px-4 py-2 text-white" type="submit">Add Lead</button>
            </form>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {leads.map((lead) => (
                    <div key={lead.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="font-medium">{lead.full_name}</p>
                        <p className="text-xs text-[var(--color-muted)]">{lead.email} | {lead.status}</p>
                    </div>
                ))}
            </div>
        </section>
    );
}

import { useEffect } from 'react';
import { useCampaignStore } from '@modules/campaign/store/campaignStore';

export function CampaignsPage() {
    const campaigns = useCampaignStore((state) => state.campaigns);
    const loading = useCampaignStore((state) => state.loading);
    const error = useCampaignStore((state) => state.error);
    const fetchCampaigns = useCampaignStore((state) => state.fetchCampaigns);
    const launchCampaign = useCampaignStore((state) => state.launchCampaign);

    useEffect(() => {
        void fetchCampaigns();
    }, [fetchCampaigns]);

    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Campaign Engine</h2>
            {loading ? <p className="text-sm">Loading...</p> : null}
            {error ? <p className="text-sm text-red-600">{error}</p> : null}
            <div className="grid gap-3">
                {campaigns.map((campaign) => (
                    <div key={campaign.id} className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
                        <p className="font-medium">{campaign.name}</p>
                        <p className="text-xs text-[var(--color-muted)]">{campaign.subject} | {campaign.status}</p>
                        {campaign.status !== 'launched' ? (
                            <button
                                type="button"
                                className="mt-2 rounded bg-[var(--color-primary)] px-3 py-1 text-xs text-white"
                                onClick={() => void launchCampaign(campaign.id)}
                            >
                                Launch
                            </button>
                        ) : null}
                    </div>
                ))}
            </div>
        </section>
    );
}

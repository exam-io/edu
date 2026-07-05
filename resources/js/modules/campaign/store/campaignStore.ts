import { create } from 'zustand';
import { campaignService } from '@modules/campaign/services/campaignService';
import type { Campaign } from '@modules/campaign/types/campaign';

interface CampaignState {
    campaigns: Campaign[];
    loading: boolean;
    error: string | null;
    fetchCampaigns: () => Promise<void>;
    launchCampaign: (id: number) => Promise<void>;
}

export const useCampaignStore = create<CampaignState>((set) => ({
    campaigns: [],
    loading: false,
    error: null,

    fetchCampaigns: async () => {
        set({ loading: true, error: null });
        try {
            const campaigns = await campaignService.list();
            set({ campaigns, loading: false });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to load campaigns.' });
        }
    },

    launchCampaign: async (id) => {
        set({ loading: true, error: null });
        try {
            const updated = await campaignService.launch(id);
            set((state) => ({
                campaigns: state.campaigns.map((campaign) => (campaign.id === id ? updated : campaign)),
                loading: false,
            }));
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to launch campaign.' });
        }
    },
}));

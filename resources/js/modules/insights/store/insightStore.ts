import { create } from 'zustand';
import { insightService } from '@modules/insights/services/insightService';
import type { GeneratedInsight } from '@modules/insights/types/insight';

interface InsightState {
    insights: GeneratedInsight[];
    loading: boolean;
    error: string | null;
    fetchInsights: () => Promise<void>;
    generateInsights: () => Promise<void>;
}

export const useInsightStore = create<InsightState>((set, get) => ({
    insights: [],
    loading: false,
    error: null,

    fetchInsights: async () => {
        set({ loading: true, error: null });
        try {
            const insights = await insightService.listInsights({ per_page: 20 });
            set({ insights, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load insights.',
            });
        }
    },

    generateInsights: async () => {
        set({ loading: true, error: null });
        try {
            await insightService.generate();
            await get().fetchInsights();
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to generate insights.',
            });
        }
    },
}));

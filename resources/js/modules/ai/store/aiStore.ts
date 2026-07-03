import { create } from 'zustand';
import { aiService } from '@modules/ai/services/aiService';
import type { AIGenerationRequest } from '@modules/ai/types/ai';

interface AIState {
    requests: AIGenerationRequest[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
}

export const useAIStore = create<AIState>((set, get) => ({
    requests: [],
    loading: false,
    initialized: false,
    error: null,

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            const requests = await aiService.list();
            set({ requests, initialized: true, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load AI generation requests.' });
        } finally {
            set({ loading: false });
        }
    },
}));

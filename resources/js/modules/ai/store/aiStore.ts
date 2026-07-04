import { create } from 'zustand';
import { aiService } from '@modules/ai/services/aiService';
import type { AIGenerationRequest } from '@modules/ai/types/ai';

interface AIState {
    requests: AIGenerationRequest[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    refresh: () => Promise<void>;
    createRequest: (payload: Record<string, unknown>) => Promise<void>;
    deleteRequest: (id: number) => Promise<void>;
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

        await get().refresh();
        set({ initialized: true });
    },

    refresh: async () => {
        set({ loading: true, error: null });
        try {
            const requests = await aiService.list();
            set({ requests, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load AI generation requests.' });
        } finally {
            set({ loading: false });
        }
    },

    createRequest: async (payload) => {
        set({ loading: true, error: null });
        try {
            const request = await aiService.create(payload);
            set((state) => ({ requests: [request, ...state.requests], error: null }));
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to create AI request.' });
        } finally {
            set({ loading: false });
        }
    },

    deleteRequest: async (id) => {
        set({ loading: true, error: null });
        try {
            await aiService.delete(id);
            set((state) => ({ requests: state.requests.filter((item) => item.id !== id), error: null }));
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to delete AI request.' });
        } finally {
            set({ loading: false });
        }
    },
}));

import { create } from 'zustand';
import { contentProcessingService } from '@modules/content-processing/services/contentProcessingService';
import type { ContentSource } from '@modules/content-processing/types/contentProcessing';

interface ContentProcessingState {
    sources: ContentSource[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
}

export const useContentProcessingStore = create<ContentProcessingState>((set, get) => ({
    sources: [],
    loading: false,
    initialized: false,
    error: null,

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            const sources = await contentProcessingService.listSources();
            set({ sources, initialized: true, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load content sources.' });
        } finally {
            set({ loading: false });
        }
    },
}));

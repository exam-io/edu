import { create } from 'zustand';
import { contentService } from '@modules/content/services/contentService';
import type { ContentItem } from '@modules/content/types/content';

interface ContentState {
    items: ContentItem[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    load: () => Promise<void>;
    initialize: () => Promise<void>;
}

export const useContentStore = create<ContentState>((set, get) => ({
    items: [],
    loading: false,
    initialized: false,
    error: null,

    load: async () => {
        try {
            const items = await contentService.listItems();
            set({ items, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load content items.' });
        }
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await get().load();
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));

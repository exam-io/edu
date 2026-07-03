import { create } from 'zustand';
import { mediaService } from '@modules/media/services/mediaService';
import type { MediaAsset } from '@modules/media/types/media';

interface MediaState {
    items: MediaAsset[];
    loading: boolean;
    initialized: boolean;
    error: string | null;
    load: () => Promise<void>;
    initialize: () => Promise<void>;
}

export const useMediaStore = create<MediaState>((set, get) => ({
    items: [],
    loading: false,
    initialized: false,
    error: null,

    load: async () => {
        try {
            const items = await mediaService.list();
            set({ items, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load media assets.' });
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

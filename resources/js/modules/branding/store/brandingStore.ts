import { create } from 'zustand';
import { brandingService } from '@modules/branding/services/brandingService';
import type { BrandingProfile, ThemePayload } from '@modules/branding/types/branding';

interface BrandingState {
    profile: BrandingProfile | null;
    theme: ThemePayload | null;
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    save: (payload: Partial<BrandingProfile>) => Promise<void>;
}

export const useBrandingStore = create<BrandingState>((set) => ({
    profile: null,
    theme: null,
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [profile, theme] = await Promise.all([brandingService.current(), brandingService.theme()]);
            set({ profile, theme });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load branding.' });
        } finally {
            set({ loading: false });
        }
    },

    save: async (payload) => {
        set({ loading: true, error: null });
        try {
            const updated = await brandingService.update(payload);
            const theme = await brandingService.theme();
            set({ profile: updated, theme });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to update branding.' });
        } finally {
            set({ loading: false });
        }
    },
}));

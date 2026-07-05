import { create } from 'zustand';
import { liveClassService } from '@modules/live-class/services/liveClassService';
import type { LiveClassSession } from '@modules/live-class/types/liveClass';

interface LiveClassState {
    sessions: LiveClassSession[];
    loading: boolean;
    error: string | null;
    fetchSessions: () => Promise<void>;
    joinSession: (id: number) => Promise<void>;
}

export const useLiveClassStore = create<LiveClassState>((set, get) => ({
    sessions: [],
    loading: false,
    error: null,

    fetchSessions: async () => {
        set({ loading: true, error: null });
        try {
            const response = await liveClassService.list();
            set({ sessions: response.data, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load live classes.',
            });
        }
    },

    joinSession: async (id: number) => {
        set({ loading: true, error: null });
        try {
            await liveClassService.join(id);
            await get().fetchSessions();
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to join live class.',
            });
        }
    },
}));

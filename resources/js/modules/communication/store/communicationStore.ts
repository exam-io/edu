import { create } from 'zustand';
import { communicationService } from '@modules/communication/services/communicationService';
import type { Announcement, CommunicationHistoryItem } from '@modules/communication/types/communication';

interface CommunicationState {
    announcements: Announcement[];
    history: CommunicationHistoryItem[];
    loading: boolean;
    error: string | null;
    fetchData: () => Promise<void>;
}

export const useCommunicationStore = create<CommunicationState>((set) => ({
    announcements: [],
    history: [],
    loading: false,
    error: null,

    fetchData: async () => {
        set({ loading: true, error: null });
        try {
            const [announcements, history] = await Promise.all([
                communicationService.listAnnouncements(),
                communicationService.listHistory(),
            ]);
            set({ announcements, history, loading: false });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to load communication data.' });
        }
    },
}));

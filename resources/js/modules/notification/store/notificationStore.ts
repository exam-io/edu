import { create } from 'zustand';
import { notificationService } from '@modules/notification/services/notificationService';
import type { TenantNotification } from '@modules/notification/types/notification';

interface NotificationState {
    items: TenantNotification[];
    loading: boolean;
    error: string | null;
    fetchNotifications: () => Promise<void>;
}

export const useNotificationStore = create<NotificationState>((set) => ({
    items: [],
    loading: false,
    error: null,

    fetchNotifications: async () => {
        set({ loading: true, error: null });
        try {
            const items = await notificationService.list();
            set({ items, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load notifications.',
            });
        }
    },
}));

import { create } from 'zustand';
import { settingsService } from '@modules/settings/services/settingsService';
import type { UserSetting } from '@modules/settings/types/setting';

interface SettingsState {
    setting: UserSetting | null;
    loading: boolean;
    initialized: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    save: (payload: Pick<UserSetting, 'language' | 'theme' | 'timezone'>) => Promise<void>;
}

export const useSettingsStore = create<SettingsState>((set, get) => ({
    setting: null,
    loading: false,
    initialized: false,
    error: null,

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            const setting = await settingsService.current();
            set({ setting, initialized: true });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load settings.' });
        } finally {
            set({ loading: false });
        }
    },

    save: async (payload) => {
        const setting = get().setting;

        if (setting === null) {
            throw new Error('Settings are not loaded yet.');
        }

        const updated = await settingsService.update(setting.id, payload);
        set({ setting: updated, error: null });
    },
}));

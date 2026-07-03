import { useSettingsStore } from '@modules/settings/store/settingsStore';

export function useSettings() {
    const setting = useSettingsStore((state) => state.setting);
    const loading = useSettingsStore((state) => state.loading);
    const error = useSettingsStore((state) => state.error);
    const initialize = useSettingsStore((state) => state.initialize);
    const save = useSettingsStore((state) => state.save);

    return {
        setting,
        loading,
        error,
        initialize,
        save,
    };
}

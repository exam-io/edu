import { useMemo } from 'react';
import { defaultTenantTheme } from '@themes/tokens';
import { useTenantSettingsStore } from '@stores/tenantSettingsStore';
import { useThemeStore } from '@stores/themeStore';

export function useTenantTheme(): Record<string, string> {
    const mode = useThemeStore((state) => state.theme);
    const { tenantPrimaryColor, tenantSecondaryColor } = useTenantSettingsStore();

    return useMemo(() => {
        return {
            ...defaultTenantTheme[mode],
            '--color-primary': tenantPrimaryColor,
            '--color-secondary': tenantSecondaryColor,
        };
    }, [mode, tenantPrimaryColor, tenantSecondaryColor]);
}

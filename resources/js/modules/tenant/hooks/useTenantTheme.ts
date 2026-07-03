import { useEffect } from 'react';
import { useTenantStore } from '@modules/tenant/store/tenantStore';
import { useThemeStore } from '@stores/themeStore';
import { useMemo } from 'react';
import { defaultTenantTheme } from '@themes/tokens';

/**
 * Hook to get tenant-specific CSS variables.
 * Merges default theme with tenant branding colors.
 */
export function useTenantTheme(): Record<string, string> {
    const mode = useThemeStore((state) => state.theme);
    const branding = useTenantStore((state) => state.branding);

    return useMemo(() => {
        const base = defaultTenantTheme[mode as 'light' | 'dark'] || defaultTenantTheme.light;
        const variables: Record<string, string> = { ...base };

        if (branding) {
            variables['--color-primary'] = branding.primary_color;
            variables['--color-secondary'] = branding.secondary_color;
        }

        return variables;
    }, [mode, branding]);
}

/**
 * Hook to apply tenant branding CSS variables to the document.
 */
export function useTenantBrandingEffect(): void {
    const variables = useTenantTheme();

    useEffect(() => {
        Object.entries(variables).forEach(([key, value]) => {
            document.documentElement.style.setProperty(key, value);
        });
    }, [variables]);
}

import { useEffect } from 'react';
import { useTenantStore } from '@modules/tenant/store/tenantStore';
import { useLocaleStore } from '@stores/localeStore';
import { useThemeStore } from '@stores/themeStore';

/**
 * Hook to boot tenant configuration on component mount.
 * Applies branding, locale, and theme from the tenant store.
 */
export function useTenantBootstrap(): void {
    const tenant = useTenantStore((state) => state.tenant);
    const branding = useTenantStore((state) => state.branding);
    const setLanguage = useLocaleStore((state) => state.setLanguage);
    const setTheme = useThemeStore((state) => state.setTheme);

    useEffect(() => {
        if (!tenant || !branding) return;

        // Apply locale from tenant settings
        if (branding.language) {
            setLanguage(branding.language as 'en' | 'hi');
        }

        // Apply theme from tenant settings
        if (branding.theme) {
            setTheme(branding.theme);
        }

        // Apply CSS variables
        document.documentElement.setAttribute('data-theme', branding.theme);
        document.documentElement.style.setProperty('--color-primary', branding.primary_color);
        document.documentElement.style.setProperty('--color-secondary', branding.secondary_color);
    }, [tenant, branding, setLanguage, setTheme]);
}

import { useEffect, type PropsWithChildren } from 'react';
import { useThemeStore } from '@stores/themeStore';
import { useTenantBrandingEffect } from '@modules/tenant';
import { useTenantStore } from '@modules/tenant/store/tenantStore';

export function ThemeProvider({ children }: PropsWithChildren) {
    const theme = useThemeStore((state) => state.theme);
    const branding = useTenantStore((state) => state.branding);
    const tenant = useTenantStore((state) => state.tenant);

    // Apply tenant branding (colors and CSS variables)
    useTenantBrandingEffect();

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);
    }, [theme]);

    useEffect(() => {
        if (tenant?.name) {
            document.title = `${tenant.name} | EduOS`;
        }

        if (!branding?.favicon) {
            return;
        }

        let faviconLink = document.querySelector("link[rel='icon']") as HTMLLinkElement | null;

        if (!faviconLink) {
            faviconLink = document.createElement('link');
            faviconLink.rel = 'icon';
            document.head.appendChild(faviconLink);
        }

        faviconLink.href = branding.favicon;
    }, [branding?.favicon, tenant?.name]);

    return <>{children}</>;
}

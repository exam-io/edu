import { useEffect, type PropsWithChildren } from 'react';
import { useThemeStore } from '@stores/themeStore';
import { useTenantBrandingEffect } from '@modules/tenant';

export function ThemeProvider({ children }: PropsWithChildren) {
    const theme = useThemeStore((state) => state.theme);
    
    // Apply tenant branding (colors and CSS variables)
    useTenantBrandingEffect();

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);
    }, [theme]);

    return <>{children}</>;
}

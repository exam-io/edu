import { useEffect, type PropsWithChildren } from 'react';
import { useThemeStore } from '@stores/themeStore';
import { useTenantTheme } from '@hooks/useTenantTheme';

export function ThemeProvider({ children }: PropsWithChildren) {
    const theme = useThemeStore((state) => state.theme);
    const variables = useTenantTheme();

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);

        Object.entries(variables).forEach(([key, value]) => {
            document.documentElement.style.setProperty(key, value);
        });
    }, [theme, variables]);

    return <>{children}</>;
}

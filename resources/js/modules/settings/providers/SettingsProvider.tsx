import { useSettings } from '@modules/settings/hooks/useSettings';
import { useEffect } from 'react';

interface SettingsProviderProps {
    children: React.ReactNode;
}

export function SettingsProvider({ children }: SettingsProviderProps) {
    const { initialize } = useSettings();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return <>{children}</>;
}

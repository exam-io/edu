import i18n from '@shared/i18n';
import { useEffect, type PropsWithChildren } from 'react';
import { useLocaleStore } from '@stores/localeStore';

const rtlLanguages = new Set(['ar', 'fa', 'he', 'ur']);

export function LocaleProvider({ children }: PropsWithChildren) {
    const language = useLocaleStore((state) => state.language);

    useEffect(() => {
        void i18n.changeLanguage(language);
        document.documentElement.lang = language;
        document.documentElement.dir = rtlLanguages.has(language) ? 'rtl' : 'ltr';
    }, [language]);

    return <>{children}</>;
}

import { create } from 'zustand';

export type LocaleCode = 'en' | 'hi';

interface LocaleState {
    language: LocaleCode;
    setLanguage: (language: LocaleCode) => void;
}

const storageKey = 'eduos:language';

const initialLanguage = (localStorage.getItem(storageKey) as LocaleCode | null) ?? 'en';

export const useLocaleStore = create<LocaleState>((set) => ({
    language: initialLanguage,
    setLanguage: (language) => {
        localStorage.setItem(storageKey, language);
        set({ language });
    },
}));

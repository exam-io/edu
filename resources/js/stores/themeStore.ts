import { create } from 'zustand';

export type ThemeMode = 'light' | 'dark';

interface ThemeState {
    theme: ThemeMode;
    setTheme: (theme: ThemeMode) => void;
}

const storageKey = 'eduos:theme';

const initialTheme = (localStorage.getItem(storageKey) as ThemeMode | null) ?? 'light';

export const useThemeStore = create<ThemeState>((set) => ({
    theme: initialTheme,
    setTheme: (theme) => {
        localStorage.setItem(storageKey, theme);
        set({ theme });
    },
}));

import { Languages, Menu, Moon, Sun } from 'lucide-react';
import type { LocaleCode } from '@stores/localeStore';
import type { ThemeMode } from '@stores/themeStore';
import { NotificationCenter } from '@components/shell/NotificationCenter';
import { ProfileMenu } from '@components/shell/ProfileMenu';
import { SearchBar } from '@components/shell/SearchBar';
import type { ShellNotification } from '@components/shell/types';

interface TopBarProps {
    search: string;
    onSearchChange: (value: string) => void;
    onSearchFocus: () => void;
    showGlobalSearch?: boolean;
    roleLabel?: string;
    theme: ThemeMode;
    onThemeToggle: () => void;
    language: LocaleCode;
    onLanguageToggle: () => void;
    onMenuOpen: () => void;
    notifications: ShellNotification[];
    userName: string;
    userEmail: string;
    onLogout: () => void;
}

export function TopBar({
    search,
    onSearchChange,
    onSearchFocus,
    showGlobalSearch = true,
    roleLabel,
    theme,
    onThemeToggle,
    language,
    onLanguageToggle,
    onMenuOpen,
    notifications,
    userName,
    userEmail,
    onLogout,
}: TopBarProps) {
    return (
        <header className="sticky top-0 z-30 flex items-center justify-between gap-2 border-b border-[var(--color-border)] bg-[color-mix(in_oklab,var(--color-surface)_88%,transparent)] px-3 py-3 backdrop-blur md:px-5">
            <div className="flex items-center gap-2">
                <button type="button" className="btn-ghost lg:hidden" onClick={onMenuOpen}>
                    <Menu size={16} />
                </button>
                {showGlobalSearch ? (
                    <SearchBar value={search} onChange={onSearchChange} onFocus={onSearchFocus} />
                ) : (
                    <span className="hidden rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.1em] text-[var(--color-muted)] md:inline-flex">
                        {roleLabel ?? 'Workspace'}
                    </span>
                )}
            </div>

            <div className="flex items-center gap-2">
                <button type="button" className="btn-ghost" onClick={onThemeToggle} aria-label="Toggle theme">
                    {theme === 'dark' ? <Sun size={16} /> : <Moon size={16} />}
                </button>
                <button type="button" className="btn-ghost" onClick={onLanguageToggle} aria-label="Toggle language">
                    <Languages size={16} />
                    <span className="hidden text-xs font-semibold uppercase sm:inline">{language}</span>
                </button>
                <NotificationCenter items={notifications} />
                <ProfileMenu userName={userName} userEmail={userEmail} onLogout={onLogout} />
            </div>
        </header>
    );
}

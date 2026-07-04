import { Clock3, Star } from 'lucide-react';
import { NavLink } from 'react-router-dom';
import type { ShellNavItem } from '@components/shell/types';

interface SidebarProps {
    open: boolean;
    collapsed: boolean;
    navItems: ShellNavItem[];
    favorites: ShellNavItem[];
    recent: ShellNavItem[];
    logo?: string;
    title: string;
    subtitle: string;
    onClose: () => void;
    onToggleCollapsed: () => void;
}

export function Sidebar({
    open,
    collapsed,
    navItems,
    favorites,
    recent,
    logo,
    title,
    subtitle,
    onClose,
    onToggleCollapsed,
}: SidebarProps) {
    const grouped = navItems.reduce<Record<string, ShellNavItem[]>>((acc, item) => {
        acc[item.group] = [...(acc[item.group] ?? []), item];
        return acc;
    }, {});

    return (
        <>
            <aside
                className={`fixed inset-y-0 left-0 z-50 overflow-hidden border-r border-[var(--color-border)] bg-[var(--color-sidebar)] p-4 shadow-[var(--shadow-elevated)] transition-all duration-200 lg:static lg:translate-x-0 ${
                    open ? 'translate-x-0' : '-translate-x-full'
                } ${collapsed ? 'w-[88px]' : 'w-[300px]'}`}
            >
                <div className="flex items-start justify-between gap-2">
                    <div className="min-w-0">
                        <div className="flex items-center gap-2">
                            {logo ? <img src={logo} alt="Tenant logo" className="h-8 w-8 rounded-lg object-cover" /> : null}
                            {!collapsed ? (
                                <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-muted)]">EduOS</p>
                            ) : null}
                        </div>
                        {!collapsed ? <h1 className="mt-2 truncate text-base font-semibold">{title}</h1> : null}
                        {!collapsed ? <p className="truncate text-xs text-[var(--color-muted)]">{subtitle}</p> : null}
                    </div>
                    <button type="button" onClick={onToggleCollapsed} className="btn-ghost hidden lg:inline-flex">
                        {collapsed ? '>' : '<'}
                    </button>
                    <button type="button" onClick={onClose} className="btn-ghost lg:hidden">
                        Close
                    </button>
                </div>

                <div className="mt-5 space-y-5 overflow-y-auto pb-4">
                    {!collapsed && favorites.length > 0 ? (
                        <section>
                            <p className="mb-2 flex items-center gap-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">
                                <Star size={12} /> Favorites
                            </p>
                            <nav className="space-y-1">
                                {favorites.map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <NavLink key={`fav-${item.path}`} to={item.path} className={({ isActive }) => `sidebar-link ${isActive ? 'sidebar-link-active' : ''}`}>
                                            <Icon size={16} />
                                            <span>{item.label}</span>
                                        </NavLink>
                                    );
                                })}
                            </nav>
                        </section>
                    ) : null}

                    {!collapsed && recent.length > 0 ? (
                        <section>
                            <p className="mb-2 flex items-center gap-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">
                                <Clock3 size={12} /> Recent
                            </p>
                            <nav className="space-y-1">
                                {recent.map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <NavLink key={`recent-${item.path}`} to={item.path} className={({ isActive }) => `sidebar-link ${isActive ? 'sidebar-link-active' : ''}`}>
                                            <Icon size={16} />
                                            <span>{item.label}</span>
                                        </NavLink>
                                    );
                                })}
                            </nav>
                        </section>
                    ) : null}

                    {Object.entries(grouped).map(([group, items]) => (
                        <section key={group}>
                            {!collapsed ? (
                                <p className="mb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[var(--color-muted)]">{group}</p>
                            ) : null}
                            <nav className="space-y-1">
                                {items.map((item) => {
                                    const Icon = item.icon;
                                    return (
                                        <NavLink
                                            key={item.path}
                                            to={item.path}
                                            className={({ isActive }) =>
                                                `sidebar-link ${isActive ? 'sidebar-link-active' : ''} ${collapsed ? 'justify-center px-2' : ''}`
                                            }
                                        >
                                            <Icon size={16} />
                                            {!collapsed ? <span>{item.label}</span> : null}
                                        </NavLink>
                                    );
                                })}
                            </nav>
                        </section>
                    ))}
                </div>
            </aside>

            {open ? <button type="button" className="fixed inset-0 z-40 bg-black/45 lg:hidden" onClick={onClose} aria-label="Close sidebar" /> : null}
        </>
    );
}

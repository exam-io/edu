import { useMemo, useState } from 'react';
import { NavLink, Outlet } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';

interface NavItem {
    label: string;
    path: string;
}

const navItems: NavItem[] = [
    { label: 'Institute Onboarding', path: '/institutes/onboarding' },
    { label: 'Academic Overview', path: '/academic' },
    { label: 'Departments', path: '/academic/departments' },
    { label: 'Programs', path: '/academic/programs' },
    { label: 'Classes', path: '/academic/classes' },
    { label: 'Sections', path: '/academic/sections' },
    { label: 'Batches', path: '/academic/batches' },
    { label: 'Subjects', path: '/academic/subjects' },
    { label: 'Assessments', path: '/assessments' },
    { label: 'Assessment Builder', path: '/assessments/builder' },
    { label: 'Result Dashboard', path: '/assessments/dashboard' },
    { label: 'Exam Overview', path: '/exams/overview' },
    { label: 'Assignment Submit', path: '/assignments/submit' },
    { label: 'Assignment Submissions', path: '/assignments/submissions' },
    { label: 'Settings', path: '/settings' },
];

function getPanelTitle(roles: string[]): string {
    if (roles.includes('Super Admin')) {
        return 'Super Admin Panel';
    }

    return 'Admin Panel';
}

export function AdminPanelLayout() {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const user = useAuthStore((state) => state.user);
    const roles = useAuthStore((state) => state.roles);
    const logout = useAuthStore((state) => state.logout);

    const panelTitle = useMemo(() => getPanelTitle(roles), [roles]);
    const roleText = roles.length > 0 ? roles.join(', ') : 'User';

    return (
        <div className="min-h-screen bg-[var(--color-bg)] text-[var(--color-text)]">
            <div className="flex min-h-screen">
                <aside
                    className={`fixed inset-y-0 left-0 z-40 w-72 border-r border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-xl transition-transform duration-200 lg:static lg:translate-x-0 lg:shadow-none ${
                        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
                    }`}
                >
                    <div className="flex items-start justify-between gap-3">
                        <div>
                            <p className="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-muted)]">EduOS</p>
                            <h1 className="mt-2 text-xl font-semibold">{panelTitle}</h1>
                            <p className="mt-1 text-xs text-[var(--color-muted)]">{roleText}</p>
                        </div>
                        <button
                            type="button"
                            className="rounded-md border border-[var(--color-border)] px-2 py-1 text-sm lg:hidden"
                            onClick={() => setMobileMenuOpen(false)}
                        >
                            Close
                        </button>
                    </div>

                    <nav className="mt-6 space-y-2">
                        {navItems.map((item) => (
                            <NavLink
                                key={item.path}
                                to={item.path}
                                onClick={() => setMobileMenuOpen(false)}
                                className={({ isActive }) =>
                                    `block rounded-lg px-3 py-2 text-sm font-medium transition ${
                                        isActive
                                            ? 'bg-[var(--color-primary)] text-white'
                                            : 'text-[var(--color-muted)] hover:bg-[color-mix(in_oklab,var(--color-primary)_10%,transparent)] hover:text-[var(--color-text)]'
                                    }`
                                }
                            >
                                {item.label}
                            </NavLink>
                        ))}
                    </nav>

                    <div className="mt-8 rounded-lg border border-[var(--color-border)] bg-[color-mix(in_oklab,var(--color-primary)_8%,white)] p-3 text-xs text-[var(--color-muted)]">
                        Logged in as
                        <p className="mt-1 text-sm font-medium text-[var(--color-text)]">{user?.name ?? 'Unknown user'}</p>
                        <p className="truncate">{user?.email ?? '-'}</p>
                    </div>
                </aside>

                {mobileMenuOpen ? (
                    <button
                        type="button"
                        className="fixed inset-0 z-30 bg-black/40 lg:hidden"
                        aria-label="Close menu"
                        onClick={() => setMobileMenuOpen(false)}
                    />
                ) : null}

                <div className="flex min-h-screen min-w-0 flex-1 flex-col">
                    <header className="sticky top-0 z-20 flex items-center justify-between border-b border-[var(--color-border)] bg-[var(--color-surface)]/95 px-4 py-3 backdrop-blur md:px-6">
                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                className="rounded-md border border-[var(--color-border)] px-3 py-1 text-sm lg:hidden"
                                onClick={() => setMobileMenuOpen(true)}
                            >
                                Menu
                            </button>
                            <div>
                                <p className="text-sm font-semibold">{panelTitle}</p>
                                <p className="text-xs text-[var(--color-muted)]">Responsive management workspace</p>
                            </div>
                        </div>

                        <button
                            type="button"
                            onClick={() => {
                                void logout();
                            }}
                            className="rounded-lg border border-[var(--color-border)] px-3 py-2 text-sm font-medium text-[var(--color-text)] transition hover:bg-[color-mix(in_oklab,var(--color-primary)_10%,transparent)]"
                        >
                            Logout
                        </button>
                    </header>

                    <main className="min-w-0 flex-1 p-3 md:p-5 lg:p-6">
                        <Outlet />
                    </main>
                </div>
            </div>
        </div>
    );
}

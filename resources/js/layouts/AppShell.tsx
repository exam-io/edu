import {
    BarChart3,
    BookOpen,
    Bot,
    Building2,
    Calendar,
    Camera,
    ClipboardList,
    GraduationCap,
    Home,
    Image,
    Library,
    Presentation,
    Settings,
    Users,
} from 'lucide-react';
import { type PropsWithChildren, useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { CommandPalette } from '@components/shell/CommandPalette';
import { Sidebar } from '@components/shell/Sidebar';
import { TopBar } from '@components/shell/TopBar';
import type { ShellNavItem, ShellNotification } from '@components/shell/types';
import { Breadcrumbs } from '@components/shell/Breadcrumbs';
import { PageHeader } from '@components/shell/PageHeader';
import { QuickActions } from '@components/shell/QuickActions';
import { useAuthStore } from '@modules/auth/store/authStore';
import { useTenantStore } from '@modules/tenant/store/tenantStore';
import { useLocaleStore } from '@stores/localeStore';
import { useThemeStore } from '@stores/themeStore';

const navItems: ShellNavItem[] = [
    { label: 'Institute Onboarding', path: '/institutes/onboarding', group: 'Institute', icon: Building2 },
    { label: 'Academic Dashboard', path: '/academic', group: 'Institute', icon: Home },
    { label: 'Departments', path: '/academic/departments', group: 'Academic', icon: Library },
    { label: 'Programs', path: '/academic/programs', group: 'Academic', icon: GraduationCap },
    { label: 'Classes', path: '/academic/classes', group: 'Academic', icon: BookOpen },
    { label: 'Sections', path: '/academic/sections', group: 'Academic', icon: ClipboardList },
    { label: 'Batches', path: '/academic/batches', group: 'Academic', icon: Calendar },
    { label: 'Subjects', path: '/academic/subjects', group: 'Academic', icon: BookOpen },
    { label: 'Students', path: '/students', group: 'People', icon: Users },
    { label: 'Teachers', path: '/teachers', group: 'People', icon: Users },
    { label: 'Parents', path: '/parents', group: 'People', icon: Users },
    { label: 'Assessments', path: '/assessments', group: 'Learning', icon: ClipboardList },
    { label: 'Exam Overview', path: '/exams/overview', group: 'Learning', icon: BarChart3 },
    { label: 'Assignments', path: '/assignments/submissions', group: 'Learning', icon: ClipboardList },
    { label: 'Courses', path: '/courses', group: 'Content', icon: BookOpen },
    { label: 'Content Library', path: '/content', group: 'Content', icon: Library },
    { label: 'Media Library', path: '/media', group: 'Content', icon: Image },
    { label: 'AI Studio', path: '/ai', group: 'Intelligence', icon: Bot },
    { label: 'Calendar', path: '/calendar', group: 'Productivity', icon: Calendar },
    { label: 'Live Classes', path: '/live-classes', group: 'Productivity', icon: Camera },
    { label: 'Teacher Dashboard', path: '/dashboard/teacher', group: 'Dashboards', icon: Presentation },
    { label: 'Student Dashboard', path: '/dashboard/student', group: 'Dashboards', icon: GraduationCap },
    { label: 'Parent Dashboard', path: '/dashboard/parent', group: 'Dashboards', icon: Users },
    { label: 'Settings', path: '/settings', group: 'System', icon: Settings },
];

const favoritePaths = ['/academic', '/students', '/assessments', '/ai'];

const demoNotifications: ShellNotification[] = [
    {
        id: 'n-1',
        title: 'Exam window opens today',
        message: 'Midterm assessments for Grade 10 begin at 10:00 AM.',
        time: '08:15',
    },
    {
        id: 'n-2',
        title: 'AI summary ready',
        message: 'Your worksheet generation job has completed.',
        time: 'Yesterday',
    },
    {
        id: 'n-3',
        title: 'Parent meeting reminder',
        message: 'PTM for Class 8 scheduled for Friday.',
        time: '2d ago',
    },
];

function readRecentPaths() {
    const key = 'eduos:recent-routes';
    try {
        const raw = localStorage.getItem(key);
        if (!raw) {
            return [] as string[];
        }
        const value = JSON.parse(raw) as string[];
        return Array.isArray(value) ? value : [];
    } catch {
        return [] as string[];
    }
}

function writeRecentPath(path: string) {
    const key = 'eduos:recent-routes';
    const current = readRecentPaths().filter((item) => item !== path);
    const next = [path, ...current].slice(0, 5);
    localStorage.setItem(key, JSON.stringify(next));
}

export function AppShell({ children }: PropsWithChildren) {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [sidebarCollapsed, setSidebarCollapsed] = useState(false);
    const [paletteOpen, setPaletteOpen] = useState(false);
    const [search, setSearch] = useState('');

    const location = useLocation();
    const navigate = useNavigate();

    const user = useAuthStore((state) => state.user);
    const roles = useAuthStore((state) => state.roles);
    const logout = useAuthStore((state) => state.logout);
    const branding = useTenantStore((state) => state.branding);
    const tenant = useTenantStore((state) => state.tenant);

    const theme = useThemeStore((state) => state.theme);
    const setTheme = useThemeStore((state) => state.setTheme);
    const language = useLocaleStore((state) => state.language);
    const setLanguage = useLocaleStore((state) => state.setLanguage);

    useEffect(() => {
        writeRecentPath(location.pathname);
    }, [location.pathname]);

    const roleText = roles.length > 0 ? roles.join(', ') : 'Institute user';
    const shellTitle = tenant?.name ?? 'EduOS Workspace';

    const favorites = navItems.filter((item) => favoritePaths.includes(item.path));
    const recent = readRecentPaths()
        .map((path) => navItems.find((item) => item.path === path))
        .filter((item): item is ShellNavItem => Boolean(item));

    return (
        <div className="min-h-screen bg-[var(--color-bg)] text-[var(--color-text)]">
            <div className="relative flex min-h-screen">
                <Sidebar
                    open={sidebarOpen}
                    collapsed={sidebarCollapsed}
                    navItems={navItems}
                    favorites={favorites}
                    recent={recent}
                    logo={branding?.logo}
                    title={shellTitle}
                    subtitle={roleText}
                    onClose={() => setSidebarOpen(false)}
                    onToggleCollapsed={() => setSidebarCollapsed((value) => !value)}
                />

                <div className="flex min-h-screen min-w-0 flex-1 flex-col">
                    <TopBar
                        search={search}
                        onSearchChange={setSearch}
                        onSearchFocus={() => setPaletteOpen(true)}
                        theme={theme}
                        onThemeToggle={() => setTheme(theme === 'dark' ? 'light' : 'dark')}
                        language={language}
                        onLanguageToggle={() => setLanguage(language === 'en' ? 'hi' : 'en')}
                        onMenuOpen={() => setSidebarOpen(true)}
                        notifications={demoNotifications}
                        userName={user?.name ?? 'User'}
                        userEmail={user?.email ?? ''}
                        onLogout={() => {
                            void logout();
                        }}
                    />

                    <main className="min-w-0 flex-1 p-3 md:p-5 lg:p-6">
                        <div className="space-y-4 animate-in">
                            <Breadcrumbs />
                            <PageHeader
                                title={shellTitle}
                                description="Run your institute from one modern workspace with AI workflows, live classes, and actionable academic insights."
                                actions={
                                    <QuickActions
                                        actions={[
                                            { label: 'Add Student', path: '/students/create' },
                                            { label: 'Create Assessment', path: '/assessments/create' },
                                        ]}
                                    />
                                }
                            />
                            {children}
                        </div>
                    </main>
                </div>
            </div>

            <CommandPalette
                open={paletteOpen}
                setOpen={setPaletteOpen}
                navItems={navItems}
                onNavigate={(path) => navigate(path)}
            />
        </div>
    );
}

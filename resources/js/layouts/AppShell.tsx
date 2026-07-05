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
    Megaphone,
    Presentation,
    Send,
    Settings,
    UserPlus,
    Users,
} from 'lucide-react';
import { type PropsWithChildren, useEffect, useMemo, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { CommandPalette } from '@components/shell/CommandPalette';
import { Sidebar } from '@components/shell/Sidebar';
import { TopBar } from '@components/shell/TopBar';
import type { ShellNavItem, ShellNotification } from '@components/shell/types';
import { Breadcrumbs } from '@components/shell/Breadcrumbs';
import { PageHeader } from '@components/shell/PageHeader';
import { QuickActions } from '@components/shell/QuickActions';
import { useAuthStore } from '@modules/auth/store/authStore';
import { getRolePanelType } from '@modules/auth/utils/roleDashboard';
import { useTenantStore } from '@modules/tenant/store/tenantStore';
import { useNotificationStore } from '@modules/notification/store/notificationStore';
import { useLocaleStore } from '@stores/localeStore';
import { useThemeStore } from '@stores/themeStore';

const allNavItems: ShellNavItem[] = [
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
    { label: 'CRM Leads', path: '/crm/leads', group: 'Growth', icon: UserPlus },
    { label: 'Admissions', path: '/admissions', group: 'Growth', icon: UserPlus },
    { label: 'Campaigns', path: '/campaigns', group: 'Growth', icon: Send },
    { label: 'Communications', path: '/communications', group: 'Growth', icon: Megaphone },
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

const roleNavigationPaths: Record<string, string[]> = {
    admin: [
        '/institutes/onboarding',
        '/academic',
        '/academic/departments',
        '/academic/programs',
        '/academic/classes',
        '/academic/sections',
        '/academic/batches',
        '/academic/subjects',
        '/students',
        '/teachers',
        '/parents',
        '/enrollments',
        '/courses',
        '/content',
        '/media',
        '/ai',
        '/calendar',
        '/live-classes',
        '/assessments',
        '/assessments/create',
        '/assessments/evaluate',
        '/assessments/dashboard',
        '/exams/overview',
        '/assignments/submissions',
        '/crm/leads',
        '/admissions',
        '/campaigns',
        '/communications',
        '/settings',
    ],
    teacher: [
        '/dashboard/teacher',
        '/live-classes',
        '/calendar',
        '/assessments/mine',
        '/assessments/evaluate',
        '/assessments/teacher-results',
        '/assignments/submissions',
        '/crm/leads',
        '/admissions',
        '/campaigns',
        '/communications',
        '/content',
        '/question-bank',
        '/settings',
    ],
    student: [
        '/dashboard/student',
        '/courses',
        '/content',
        '/calendar',
        '/live-classes',
        '/assessments/mine',
        '/assessments/attempt',
        '/assignments/submit',
        '/settings',
    ],
    parent: [
        '/dashboard/parent',
        '/calendar',
        '/live-classes',
        '/assignments/submissions',
        '/settings',
    ],
    general: ['/academic', '/calendar', '/settings'],
};

const roleFavoritePaths: Record<string, string[]> = {
    admin: ['/academic', '/students', '/assessments', '/crm/leads'],
    teacher: ['/dashboard/teacher', '/live-classes', '/assessments/mine'],
    student: ['/dashboard/student', '/courses', '/assessments/mine'],
    parent: ['/dashboard/parent', '/calendar'],
    general: ['/academic'],
};

const roleShellCopy: Record<
    string,
    {
        title: string;
        description: string;
        quickActions: Array<{ label: string; path: string }>;
        roleLabel: string;
        showGlobalSearch: boolean;
    }
> = {
    admin: {
        title: 'Institute Command Center',
        description: 'Manage academics, people, analytics, and AI operations across your tenant with full administrative control.',
        quickActions: [
            { label: 'Add Student', path: '/students/create' },
            { label: 'Create Assessment', path: '/assessments/create' },
        ],
        roleLabel: 'Admin Console',
        showGlobalSearch: true,
    },
    teacher: {
        title: 'Teacher Workspace',
        description: 'Run classes, evaluate submissions, publish assessments, and keep learner performance on track.',
        quickActions: [
            { label: 'Evaluate Submissions', path: '/assessments/evaluate' },
            { label: 'Teacher Results', path: '/assessments/teacher-results' },
        ],
        roleLabel: 'Teacher Panel',
        showGlobalSearch: true,
    },
    student: {
        title: 'Student Learning Hub',
        description: 'See your classes, assignments, and upcoming assessments in a focused learner experience.',
        quickActions: [
            { label: 'Start Attempt', path: '/assessments/attempt' },
            { label: 'Submit Assignment', path: '/assignments/submit' },
        ],
        roleLabel: 'Student Panel',
        showGlobalSearch: false,
    },
    parent: {
        title: 'Parent Engagement Portal',
        description: 'Track attendance, assignment updates, and institute announcements for your child in one clear view.',
        quickActions: [
            { label: 'View Calendar', path: '/calendar' },
            { label: 'Open Live Classes', path: '/live-classes' },
        ],
        roleLabel: 'Parent Panel',
        showGlobalSearch: false,
    },
    general: {
        title: 'EduOS Workspace',
        description: 'Run your institute from one modern workspace with AI workflows, live classes, and actionable academic insights.',
        quickActions: [{ label: 'Open Academic', path: '/academic' }],
        roleLabel: 'Workspace',
        showGlobalSearch: true,
    },
};

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
    const notifications = useNotificationStore((state) => state.items);
    const fetchNotifications = useNotificationStore((state) => state.fetchNotifications);
    const branding = useTenantStore((state) => state.branding);
    const tenant = useTenantStore((state) => state.tenant);

    const theme = useThemeStore((state) => state.theme);
    const setTheme = useThemeStore((state) => state.setTheme);
    const language = useLocaleStore((state) => state.language);
    const setLanguage = useLocaleStore((state) => state.setLanguage);

    const panelType = useMemo(() => getRolePanelType(roles), [roles]);
    const allowedPaths = roleNavigationPaths[panelType];
    const scopedNavItems = useMemo(() => allNavItems.filter((item) => allowedPaths.includes(item.path)), [allowedPaths]);
    const shellCopy = roleShellCopy[panelType];

    useEffect(() => {
        writeRecentPath(location.pathname);
    }, [location.pathname]);

    useEffect(() => {
        void fetchNotifications();
    }, [fetchNotifications]);

    const roleText = roles.length > 0 ? roles.join(', ') : 'Institute user';
    const shellTitle = tenant?.name ? `${tenant.name} • ${shellCopy.roleLabel}` : shellCopy.roleLabel;

    const favorites = scopedNavItems.filter((item) => roleFavoritePaths[panelType].includes(item.path));
    const recent = readRecentPaths()
        .map((path) => scopedNavItems.find((item) => item.path === path))
        .filter((item): item is ShellNavItem => Boolean(item));

    const shellNotifications: ShellNotification[] = notifications.slice(0, 5).map((notification) => ({
        id: String(notification.id),
        title: notification.title,
        message: notification.body ?? notification.notification_type,
        time: new Date(notification.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    }));

    return (
        <div className="min-h-screen bg-[var(--color-bg)] text-[var(--color-text)]">
            <div className="relative flex min-h-screen">
                <Sidebar
                    open={sidebarOpen}
                    collapsed={sidebarCollapsed}
                    navItems={scopedNavItems}
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
                        showGlobalSearch={shellCopy.showGlobalSearch}
                        roleLabel={shellCopy.roleLabel}
                        theme={theme}
                        onThemeToggle={() => setTheme(theme === 'dark' ? 'light' : 'dark')}
                        language={language}
                        onLanguageToggle={() => setLanguage(language === 'en' ? 'hi' : 'en')}
                        onMenuOpen={() => setSidebarOpen(true)}
                        notifications={shellNotifications}
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
                                title={shellCopy.title}
                                description={shellCopy.description}
                                actions={
                                    <QuickActions
                                        actions={shellCopy.quickActions}
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
                navItems={scopedNavItems}
                onNavigate={(path) => navigate(path)}
            />
        </div>
    );
}

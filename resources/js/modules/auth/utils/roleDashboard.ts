export type RolePanelType = 'admin' | 'teacher' | 'student' | 'parent' | 'general';

const roleLandingMap: Array<{ names: string[]; path: string }> = [
    {
        names: ['super admin', 'institute admin', 'tenant-admin', 'tenant manager', 'tenant-manager'],
        path: '/academic',
    },
    {
        names: ['teacher', 'faculty'],
        path: '/dashboard/teacher',
    },
    {
        names: ['student'],
        path: '/dashboard/student',
    },
    {
        names: ['parent'],
        path: '/dashboard/parent',
    },
];

function normalize(value: string): string {
    return value.trim().toLowerCase();
}

export function getRolePanelType(roles: string[]): RolePanelType {
    const normalized = roles.map(normalize);

    if (normalized.some((role) => ['super admin', 'institute admin', 'tenant-admin', 'tenant manager', 'tenant-manager'].includes(role))) {
        return 'admin';
    }

    if (normalized.some((role) => ['teacher', 'faculty'].includes(role))) {
        return 'teacher';
    }

    if (normalized.some((role) => role === 'student')) {
        return 'student';
    }

    if (normalized.some((role) => role === 'parent')) {
        return 'parent';
    }

    return 'general';
}

export function getRoleDefaultPath(roles: string[]): string {
    const normalized = roles.map(normalize);

    for (const matcher of roleLandingMap) {
        const hasMatch = matcher.names.some((name) => normalized.includes(name));
        if (hasMatch) {
            return matcher.path;
        }
    }

    return '/academic';
}

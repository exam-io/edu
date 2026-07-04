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

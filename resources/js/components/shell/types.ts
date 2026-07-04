import type { LucideIcon } from 'lucide-react';

export interface ShellNavItem {
    label: string;
    path: string;
    group: string;
    icon: LucideIcon;
}

export interface ShellNotification {
    id: string;
    title: string;
    message: string;
    time: string;
}

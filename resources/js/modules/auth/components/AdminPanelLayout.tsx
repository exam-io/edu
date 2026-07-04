import { Outlet } from 'react-router-dom';
import { AppShell } from '@layouts/AppShell';

export function AdminPanelLayout() {
    return <AppShell><Outlet /></AppShell>;
}

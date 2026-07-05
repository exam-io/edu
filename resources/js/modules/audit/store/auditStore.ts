import { create } from 'zustand';
import { auditService } from '@modules/audit/services/auditService';
import type { AuditLogEntry } from '@modules/audit/types/audit';

interface AuditState {
    logs: AuditLogEntry[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    logDashboardAccess: () => Promise<void>;
}

export const useAuditStore = create<AuditState>((set) => ({
    logs: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const logs = await auditService.logs();
            set({ logs });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load audit logs.' });
        } finally {
            set({ loading: false });
        }
    },

    logDashboardAccess: async () => {
        set({ loading: true, error: null });
        try {
            await auditService.store({
                action: 'operations.dashboard.opened',
                resource_type: 'frontend.page',
                resource_id: 'audit-overview',
                context: { source: 'web-ui' },
            });
            const logs = await auditService.logs();
            set({ logs });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to write audit event.' });
        } finally {
            set({ loading: false });
        }
    },
}));

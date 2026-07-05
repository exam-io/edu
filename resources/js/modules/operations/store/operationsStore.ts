import { create } from 'zustand';
import { operationsService } from '@modules/operations/services/operationsService';
import type { BackupExecution, QueueOperationLog } from '@modules/operations/types/operations';

interface OperationsState {
    latestBackup: BackupExecution | null;
    queueLogs: QueueOperationLog[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    runBackup: () => Promise<void>;
}

export const useOperationsStore = create<OperationsState>((set) => ({
    latestBackup: null,
    queueLogs: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [latestBackup, queueLogs] = await Promise.all([
                operationsService.latestBackup(),
                operationsService.queueLogs(),
            ]);
            set({ latestBackup, queueLogs });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load operations data.' });
        } finally {
            set({ loading: false });
        }
    },

    runBackup: async () => {
        set({ loading: true, error: null });
        try {
            await operationsService.triggerBackup();
            await operationsService.recordQueueOperation('backup.triggered', { source: 'frontend' });
            const [latestBackup, queueLogs] = await Promise.all([
                operationsService.latestBackup(),
                operationsService.queueLogs(),
            ]);
            set({ latestBackup, queueLogs });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to trigger backup.' });
        } finally {
            set({ loading: false });
        }
    },
}));

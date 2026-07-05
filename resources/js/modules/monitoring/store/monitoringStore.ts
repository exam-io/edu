import { create } from 'zustand';
import { monitoringService } from '@modules/monitoring/services/monitoringService';
import type { AlertIncident, MetricSnapshot } from '@modules/monitoring/types/monitoring';

interface MonitoringState {
    metrics: MetricSnapshot[];
    incidents: AlertIncident[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    triggerAggregation: () => Promise<void>;
}

export const useMonitoringStore = create<MonitoringState>((set) => ({
    metrics: [],
    incidents: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const metrics = await monitoringService.metrics();
            set({ metrics });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load monitoring metrics.' });
        } finally {
            set({ loading: false });
        }
    },

    triggerAggregation: async () => {
        set({ loading: true, error: null });
        try {
            const incidents = await monitoringService.aggregate({
                metric_key: 'queue_backlog',
                period_key: new Date().toISOString().slice(0, 13),
                value: 45,
                meta: { source: 'frontend' },
            });
            set({ incidents });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to aggregate monitoring metrics.' });
        } finally {
            set({ loading: false });
        }
    },
}));

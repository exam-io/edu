import { create } from 'zustand';
import { analyticsService } from '@modules/analytics/services/analyticsService';
import type { AnalyticsEvent, MetricSnapshot } from '@modules/analytics/types/analytics';

interface AnalyticsState {
    events: AnalyticsEvent[];
    snapshots: MetricSnapshot[];
    loading: boolean;
    error: string | null;
    fetchEvents: () => Promise<void>;
    fetchSnapshots: (metricKey?: string) => Promise<void>;
}

export const useAnalyticsStore = create<AnalyticsState>((set) => ({
    events: [],
    snapshots: [],
    loading: false,
    error: null,

    fetchEvents: async () => {
        set({ loading: true, error: null });
        try {
            const events = await analyticsService.listEvents({ per_page: 20 });
            set({ events, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load analytics events.',
            });
        }
    },

    fetchSnapshots: async (metricKey = 'events.count') => {
        set({ loading: true, error: null });
        try {
            const snapshots = await analyticsService.metricSeries(metricKey);
            set({ snapshots, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load metrics.',
            });
        }
    },
}));

import { create } from 'zustand';
import { reportingService } from '@modules/reporting/services/reportingService';
import type { ReportTemplate } from '@modules/reporting/types/reporting';

interface ReportingState {
    templates: ReportTemplate[];
    loading: boolean;
    error: string | null;
    fetchTemplates: () => Promise<void>;
}

export const useReportingStore = create<ReportingState>((set) => ({
    templates: [],
    loading: false,
    error: null,

    fetchTemplates: async () => {
        set({ loading: true, error: null });
        try {
            const templates = await reportingService.listTemplates({ per_page: 20 });
            set({ templates, loading: false });
        } catch (error) {
            set({
                loading: false,
                error: error instanceof Error ? error.message : 'Failed to load reports.',
            });
        }
    },
}));

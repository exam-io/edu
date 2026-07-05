import { create } from 'zustand';
import { admissionsService } from '@modules/admissions/services/admissionsService';
import type { AdmissionApplication } from '@modules/admissions/types/admissions';

interface AdmissionsState {
    applications: AdmissionApplication[];
    loading: boolean;
    error: string | null;
    fetchApplications: () => Promise<void>;
}

export const useAdmissionsStore = create<AdmissionsState>((set) => ({
    applications: [],
    loading: false,
    error: null,

    fetchApplications: async () => {
        set({ loading: true, error: null });
        try {
            const applications = await admissionsService.list();
            set({ applications, loading: false });
        } catch (error) {
            set({ loading: false, error: error instanceof Error ? error.message : 'Failed to load applications.' });
        }
    },
}));

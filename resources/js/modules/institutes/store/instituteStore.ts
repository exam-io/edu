import { create } from 'zustand';
import { instituteService } from '@modules/institutes/services/instituteService';
import type {
    AcademicSession,
    CreateAcademicSessionPayload,
    Institute,
    OnboardingWizard,
    RegisterInstitutePayload,
    UpdateBrandingPayload,
    UpdateInstitutePayload,
} from '@modules/institutes/types/institute';

interface InstituteState {
    institute: Institute | null;
    onboarding: OnboardingWizard | null;
    academicSessions: AcademicSession[];
    loading: boolean;
    error: string | null;

    initialize: () => Promise<void>;
    registerInstitute: (payload: RegisterInstitutePayload) => Promise<Institute>;
    updateInstitute: (payload: UpdateInstitutePayload) => Promise<Institute>;
    updateBranding: (payload: UpdateBrandingPayload) => Promise<Institute>;
    loadOnboarding: () => Promise<void>;
    loadAcademicSessions: () => Promise<void>;
    createAcademicSession: (payload: CreateAcademicSessionPayload) => Promise<AcademicSession>;
    updateAcademicSession: (sessionId: number, payload: Partial<CreateAcademicSessionPayload>) => Promise<AcademicSession>;
    deleteAcademicSession: (sessionId: number) => Promise<void>;
}

export const useInstituteStore = create<InstituteState>((set, get) => ({
    institute: null,
    onboarding: null,
    academicSessions: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });

        try {
            const institute = await instituteService.getCurrent();
            set({ institute });
            await Promise.all([get().loadOnboarding(), get().loadAcademicSessions()]);
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to initialize institute context.' });
        } finally {
            set({ loading: false });
        }
    },

    registerInstitute: async (payload) => {
        set({ loading: true, error: null });

        try {
            const institute = await instituteService.register(payload);
            set({ institute });
            await Promise.all([get().loadOnboarding(), get().loadAcademicSessions()]);
            return institute;
        } catch (error) {
            const message = error instanceof Error ? error.message : 'Failed to register institute.';
            set({ error: message });
            throw new Error(message);
        } finally {
            set({ loading: false });
        }
    },

    updateInstitute: async (payload) => {
        const institute = get().institute;
        if (!institute) {
            throw new Error('Institute context is not initialized.');
        }

        set({ loading: true, error: null });

        try {
            const updated = await instituteService.update(institute.id, payload);
            set({ institute: updated });
            await get().loadOnboarding();
            return updated;
        } catch (error) {
            const message = error instanceof Error ? error.message : 'Failed to update institute.';
            set({ error: message });
            throw new Error(message);
        } finally {
            set({ loading: false });
        }
    },

    updateBranding: async (payload) => {
        const institute = get().institute;
        if (!institute) {
            throw new Error('Institute context is not initialized.');
        }

        set({ loading: true, error: null });

        try {
            const updated = await instituteService.updateBranding(institute.id, payload);
            set({ institute: updated });
            await get().loadOnboarding();
            return updated;
        } catch (error) {
            const message = error instanceof Error ? error.message : 'Failed to update branding.';
            set({ error: message });
            throw new Error(message);
        } finally {
            set({ loading: false });
        }
    },

    loadOnboarding: async () => {
        const institute = get().institute;
        if (!institute) {
            return;
        }

        try {
            const onboarding = await instituteService.getOnboarding(institute.id);
            set({ onboarding });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load onboarding status.' });
        }
    },

    loadAcademicSessions: async () => {
        const institute = get().institute;
        if (!institute) {
            return;
        }

        try {
            const academicSessions = await instituteService.listAcademicSessions(institute.id);
            set({ academicSessions });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load academic sessions.' });
        }
    },

    createAcademicSession: async (payload) => {
        const institute = get().institute;
        if (!institute) {
            throw new Error('Institute context is not initialized.');
        }

        const created = await instituteService.createAcademicSession(institute.id, payload);
        set((state) => ({ academicSessions: [created, ...state.academicSessions] }));
        await get().loadOnboarding();
        return created;
    },

    updateAcademicSession: async (sessionId, payload) => {
        const institute = get().institute;
        if (!institute) {
            throw new Error('Institute context is not initialized.');
        }

        const updated = await instituteService.updateAcademicSession(institute.id, sessionId, payload);
        set((state) => ({
            academicSessions: state.academicSessions.map((session) =>
                session.id === updated.id ? updated : session
            ),
        }));
        return updated;
    },

    deleteAcademicSession: async (sessionId) => {
        const institute = get().institute;
        if (!institute) {
            throw new Error('Institute context is not initialized.');
        }

        await instituteService.deleteAcademicSession(institute.id, sessionId);
        set((state) => ({
            academicSessions: state.academicSessions.filter((session) => session.id !== sessionId),
        }));
    },
}));

import { useInstituteStore } from '@modules/institutes/store/instituteStore';

export function useInstitute() {
    const institute = useInstituteStore((state) => state.institute);
    const onboarding = useInstituteStore((state) => state.onboarding);
    const academicSessions = useInstituteStore((state) => state.academicSessions);
    const loading = useInstituteStore((state) => state.loading);
    const error = useInstituteStore((state) => state.error);
    const initialize = useInstituteStore((state) => state.initialize);
    const registerInstitute = useInstituteStore((state) => state.registerInstitute);
    const updateInstitute = useInstituteStore((state) => state.updateInstitute);
    const updateBranding = useInstituteStore((state) => state.updateBranding);
    const loadOnboarding = useInstituteStore((state) => state.loadOnboarding);
    const loadAcademicSessions = useInstituteStore((state) => state.loadAcademicSessions);
    const createAcademicSession = useInstituteStore((state) => state.createAcademicSession);
    const updateAcademicSession = useInstituteStore((state) => state.updateAcademicSession);
    const deleteAcademicSession = useInstituteStore((state) => state.deleteAcademicSession);

    return {
        institute,
        onboarding,
        academicSessions,
        loading,
        error,
        initialize,
        registerInstitute,
        updateInstitute,
        updateBranding,
        loadOnboarding,
        loadAcademicSessions,
        createAcademicSession,
        updateAcademicSession,
        deleteAcademicSession,
    };
}

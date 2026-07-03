export { InstituteOnboardingPage } from '@modules/institutes/pages/InstituteOnboardingPage';

export { InstituteProvider } from '@modules/institutes/providers/InstituteProvider';
export { OnboardingWizard } from '@modules/institutes/components/OnboardingWizard';

export { useInstitute } from '@modules/institutes/hooks/useInstitute';
export { useInstituteStore } from '@modules/institutes/store/instituteStore';
export { instituteService } from '@modules/institutes/services/instituteService';

export type {
	AcademicSession,
	CreateAcademicSessionPayload,
	Institute,
	InstituteEnvelope,
	OnboardingWizard as OnboardingWizardModel,
	RegisterInstitutePayload,
	UpdateBrandingPayload,
	UpdateInstitutePayload,
} from '@modules/institutes/types/institute';

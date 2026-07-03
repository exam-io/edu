export { AcademicProvider } from '@modules/academic/providers/AcademicProvider';

export { AcademicHomePage } from '@modules/academic/pages/AcademicHomePage';
export { DepartmentsPage } from '@modules/academic/pages/DepartmentsPage';
export { ProgramsPage } from '@modules/academic/pages/ProgramsPage';
export { ClassesPage } from '@modules/academic/pages/ClassesPage';
export { SectionsPage } from '@modules/academic/pages/SectionsPage';
export { BatchesPage } from '@modules/academic/pages/BatchesPage';
export { SubjectsPage } from '@modules/academic/pages/SubjectsPage';

export { useAcademic } from '@modules/academic/hooks/useAcademic';
export { useAcademicStore } from '@modules/academic/store/academicStore';
export { academicService } from '@modules/academic/services/academicService';

export type {
    AcademicClass,
    AcademicEntity,
    AcademicEntityKey,
    AcademicEnvelope,
    Batch,
    Department,
    Program,
    Section,
    Subject,
} from '@modules/academic/types/academic';

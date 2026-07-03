import { GuestRoute } from '@modules/auth/routes/GuestRoute';
import { PermissionRoute } from '@modules/auth/routes/PermissionRoute';
import { ProtectedRoute } from '@modules/auth/routes/ProtectedRoute';
import { ForgotPasswordPage } from '@modules/auth/pages/ForgotPasswordPage';
import { LoginPage } from '@modules/auth/pages/LoginPage';
import { ResetPasswordPage } from '@modules/auth/pages/ResetPasswordPage';
import { VerifyEmailPage } from '@modules/auth/pages/VerifyEmailPage';
import { AdminPanelLayout } from '@modules/auth/components/AdminPanelLayout';
import { InstituteOnboardingPage } from '@modules/institutes/pages/InstituteOnboardingPage';
import {
    AcademicHomePage,
    BatchesPage,
    ClassesPage,
    DepartmentsPage,
    ProgramsPage,
    SectionsPage,
    SubjectsPage,
} from '@modules/academic';
import {
    StudentCreatePage,
    StudentEditPage,
    StudentProfilePage,
    StudentsListPage,
} from '@modules/students';
import {
    TeacherCreatePage,
    TeacherEditPage,
    TeacherProfilePage,
    TeachersListPage,
} from '@modules/teachers';
import {
    ParentCreatePage,
    ParentEditPage,
    ParentProfilePage,
    ParentsListPage,
} from '@modules/parents';
import {
    EnrollmentCreatePage,
    EnrollmentEditPage,
    EnrollmentsListPage,
    TeacherAssignmentCreatePage,
    TeacherAssignmentEditPage,
    TeacherAssignmentsListPage,
} from '@modules/enrollments';
import { SettingsPage } from '@modules/settings';
import { CoursesPage } from '@modules/course';
import { ContentLibraryPage } from '@modules/content';
import { MediaLibraryPage } from '@modules/media';
import { LmsOverviewPage } from '@modules/lms';
import { Navigate, Route, Routes } from 'react-router-dom';
import { TenantBootstrapProvider } from '@modules/tenant';

export function AppRoutes() {
    return (
        <TenantBootstrapProvider>
            <Routes>
                <Route element={<GuestRoute />}>
                    <Route path="/auth/login" element={<LoginPage />} />
                    <Route path="/auth/forgot-password" element={<ForgotPasswordPage />} />
                    <Route path="/auth/reset-password" element={<ResetPasswordPage />} />
                </Route>

                <Route element={<ProtectedRoute />}>
                    <Route element={<AdminPanelLayout />}>
                        <Route element={<PermissionRoute />}>
                            <Route path="/auth/verify-email" element={<VerifyEmailPage />} />
                            <Route path="/institutes/onboarding" element={<InstituteOnboardingPage />} />
                            <Route path="/academic" element={<AcademicHomePage />} />
                            <Route path="/academic/departments" element={<DepartmentsPage />} />
                            <Route path="/academic/programs" element={<ProgramsPage />} />
                            <Route path="/academic/classes" element={<ClassesPage />} />
                            <Route path="/academic/sections" element={<SectionsPage />} />
                            <Route path="/academic/batches" element={<BatchesPage />} />
                            <Route path="/academic/subjects" element={<SubjectsPage />} />

                            <Route path="/students" element={<StudentsListPage />} />
                            <Route path="/students/create" element={<StudentCreatePage />} />
                            <Route path="/students/:id/edit" element={<StudentEditPage />} />
                            <Route path="/students/:id/profile" element={<StudentProfilePage />} />

                            <Route path="/teachers" element={<TeachersListPage />} />
                            <Route path="/teachers/create" element={<TeacherCreatePage />} />
                            <Route path="/teachers/:id/edit" element={<TeacherEditPage />} />
                            <Route path="/teachers/:id/profile" element={<TeacherProfilePage />} />

                            <Route path="/parents" element={<ParentsListPage />} />
                            <Route path="/parents/create" element={<ParentCreatePage />} />
                            <Route path="/parents/:id/edit" element={<ParentEditPage />} />
                            <Route path="/parents/:id/profile" element={<ParentProfilePage />} />

                            <Route path="/enrollments" element={<EnrollmentsListPage />} />
                            <Route path="/enrollments/create" element={<EnrollmentCreatePage />} />
                            <Route path="/enrollments/:id/edit" element={<EnrollmentEditPage />} />

                            <Route path="/courses" element={<CoursesPage />} />
                            <Route path="/content" element={<ContentLibraryPage />} />
                            <Route path="/media" element={<MediaLibraryPage />} />
                            <Route path="/lms" element={<LmsOverviewPage />} />

                            <Route path="/teacher-assignments" element={<TeacherAssignmentsListPage />} />
                            <Route path="/teacher-assignments/create" element={<TeacherAssignmentCreatePage />} />
                            <Route path="/teacher-assignments/:id/edit" element={<TeacherAssignmentEditPage />} />

                            <Route path="/settings" element={<SettingsPage />} />

                            <Route
                                path="/"
                                element={<Navigate to="/institutes/onboarding" replace />}
                            />
                        </Route>
                    </Route>
                </Route>

                <Route path="*" element={<Navigate to="/auth/login" replace />} />
            </Routes>
        </TenantBootstrapProvider>
    );
}

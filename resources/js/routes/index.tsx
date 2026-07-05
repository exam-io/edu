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
import { AIGenerationPage } from '@modules/ai';
import { ContentProcessingPage } from '@modules/content-processing';
import { QuestionBankPage } from '@modules/question-bank';
import {
    AssessmentBuilderPage,
    AssessmentInstructionsPage,
    AssessmentListPage,
    AttemptScreenPage,
    CreateAssessmentPage,
    EvaluateSubmissionsPage,
    MyAssessmentsPage,
    PublishAssessmentPage,
    ResultDashboardPage,
    ResultScreenPage,
    TeacherResultsPage,
} from '@modules/assessment';
import { ExamOverviewPage } from '@modules/exam';
import { AssignmentSubmissionPage, AssignmentSubmissionsPage } from '@modules/assignment';
import { LeadsPage } from '@modules/crm';
import { AdmissionsPage } from '@modules/admissions';
import { CampaignsPage } from '@modules/campaign';
import { AnnouncementsPage } from '@modules/communication';
import { AnalyticsOverviewPage } from '@modules/analytics';
import { ReportBuilderPage, ReportLibraryPage, ScheduledReportsPage } from '@modules/reporting';
import { InsightsCenterPage } from '@modules/insights';
import { BrandingSettingsPage } from '@modules/branding';
import { FeatureFlagsPage } from '@modules/feature-management';
import { DomainSettingsPage, NavigationSettingsPage } from '@modules/white-label';
import { MobileProvisioningPage } from '@modules/mobile-provisioning';
import { BillingCenterPage } from '@modules/billing';
import { SubscriptionManagementPage } from '@modules/subscription';
import { PaymentTransactionsPage } from '@modules/payment';
import { RevenueDashboardPage } from '@modules/saas';
import { SystemSecurityPage } from '@modules/system';
import { MonitoringDashboardPage } from '@modules/monitoring';
import { AuditTrailPage } from '@modules/audit';
import { OperationsCenterPage } from '@modules/operations';
import { Navigate, Route, Routes } from 'react-router-dom';
import { TenantBootstrapProvider } from '@modules/tenant';
import { CalendarPage } from '@modules/dashboard/pages/CalendarPage';
import { LiveClassesPage } from '@modules/dashboard/pages/LiveClassesPage';
import { TeacherDashboardPage } from '@modules/dashboard/pages/TeacherDashboardPage';
import { StudentDashboardPage } from '@modules/dashboard/pages/StudentDashboardPage';
import { ParentDashboardPage } from '@modules/dashboard/pages/ParentDashboardPage';
import { AdminAnalyticsDashboardPage } from '@modules/dashboard/pages/AdminAnalyticsDashboardPage';
import { RoleHomeRedirect } from '@modules/auth/routes/RoleHomeRedirect';

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
                            <Route path="/ai" element={<AIGenerationPage />} />
                            <Route path="/content-processing" element={<ContentProcessingPage />} />
                            <Route path="/question-bank" element={<QuestionBankPage />} />
                            <Route path="/calendar" element={<CalendarPage />} />
                            <Route path="/live-classes" element={<LiveClassesPage />} />
                            <Route path="/dashboard/teacher" element={<TeacherDashboardPage />} />
                            <Route path="/dashboard/student" element={<StudentDashboardPage />} />
                            <Route path="/dashboard/parent" element={<ParentDashboardPage />} />
                            <Route path="/dashboard/analytics" element={<AdminAnalyticsDashboardPage />} />
                            <Route path="/analytics" element={<AnalyticsOverviewPage />} />
                            <Route path="/reports" element={<ReportLibraryPage />} />
                            <Route path="/reports/builder" element={<ReportBuilderPage />} />
                            <Route path="/reports/scheduled" element={<ScheduledReportsPage />} />
                            <Route path="/insights" element={<InsightsCenterPage />} />
                            <Route path="/assessments" element={<AssessmentListPage />} />
                            <Route path="/assessments/create" element={<CreateAssessmentPage />} />
                            <Route path="/assessments/builder" element={<AssessmentBuilderPage />} />
                            <Route path="/assessments/publish" element={<PublishAssessmentPage />} />
                            <Route path="/assessments/dashboard" element={<ResultDashboardPage />} />
                            <Route path="/assessments/mine" element={<MyAssessmentsPage />} />
                            <Route path="/assessments/evaluate" element={<EvaluateSubmissionsPage />} />
                            <Route path="/assessments/teacher-results" element={<TeacherResultsPage />} />
                            <Route path="/assessments/instructions" element={<AssessmentInstructionsPage />} />
                            <Route path="/assessments/attempt" element={<AttemptScreenPage />} />
                            <Route path="/assessments/result" element={<ResultScreenPage />} />
                            <Route path="/exams/overview" element={<ExamOverviewPage />} />
                            <Route path="/assignments/submit" element={<AssignmentSubmissionPage />} />
                            <Route path="/assignments/submissions" element={<AssignmentSubmissionsPage />} />
                            <Route path="/crm/leads" element={<LeadsPage />} />
                            <Route path="/admissions" element={<AdmissionsPage />} />
                            <Route path="/campaigns" element={<CampaignsPage />} />
                            <Route path="/communications" element={<AnnouncementsPage />} />

                            <Route path="/teacher-assignments" element={<TeacherAssignmentsListPage />} />
                            <Route path="/teacher-assignments/create" element={<TeacherAssignmentCreatePage />} />
                            <Route path="/teacher-assignments/:id/edit" element={<TeacherAssignmentEditPage />} />

                            <Route path="/settings" element={<SettingsPage />} />
                            <Route path="/settings/branding" element={<BrandingSettingsPage />} />
                            <Route path="/settings/feature-flags" element={<FeatureFlagsPage />} />
                            <Route path="/settings/domains" element={<DomainSettingsPage />} />
                            <Route path="/settings/navigation" element={<NavigationSettingsPage />} />
                            <Route path="/settings/mobile-provisioning" element={<MobileProvisioningPage />} />
                            <Route path="/settings/billing" element={<BillingCenterPage />} />
                            <Route path="/settings/subscription" element={<SubscriptionManagementPage />} />
                            <Route path="/settings/payments" element={<PaymentTransactionsPage />} />
                            <Route path="/settings/revenue" element={<RevenueDashboardPage />} />
                            <Route path="/settings/system" element={<SystemSecurityPage />} />
                            <Route path="/settings/monitoring" element={<MonitoringDashboardPage />} />
                            <Route path="/settings/audit" element={<AuditTrailPage />} />
                            <Route path="/settings/operations" element={<OperationsCenterPage />} />

                            <Route
                                path="/"
                                element={<RoleHomeRedirect />}
                            />
                        </Route>
                    </Route>
                </Route>

                <Route path="*" element={<Navigate to="/auth/login" replace />} />
            </Routes>
        </TenantBootstrapProvider>
    );
}

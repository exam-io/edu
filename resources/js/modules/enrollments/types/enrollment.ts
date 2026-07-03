export interface Enrollment {
    id: number;
    tenant_id: number;
    student_id: number;
    academic_session_id: number;
    class_id: number;
    section_id: number | null;
    batch_id: number | null;
    enrollment_date: string;
    status: string;
}

export interface TeacherAssignment {
    id: number;
    tenant_id: number;
    teacher_id: number;
    academic_session_id: number;
    class_id: number | null;
    section_id: number | null;
    batch_id: number | null;
    subject_id: number | null;
    start_date: string;
    end_date: string | null;
    status: string;
}

export interface EnrollmentEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

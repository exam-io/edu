export interface AssignmentSubmission {
    id: number;
    assessment_id: number;
    student_id: number;
    file_path: string;
    submitted_at?: string | null;
    score?: number | null;
    feedback?: string | null;
    status: string;
}

export interface AssignmentEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface LmsEnrollment {
    id: number;
    course_id: number;
    student_id: number;
    enrolled_at: string;
    status: string;
}

export interface LmsProgress {
    id: number;
    course_id: number;
    student_id: number;
    progress_percent: number;
    status: string;
}

export interface LmsEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

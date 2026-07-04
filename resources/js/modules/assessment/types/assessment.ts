export interface AssessmentQuestion {
    id: number;
    question_id: number;
    marks: number;
    sort_order: number;
    question?: {
        id: number;
        stem: string;
        question_type: string;
        options: unknown[];
    } | null;
}

export interface Assessment {
    id: number;
    title: string;
    description?: string | null;
    type: string;
    instructions?: string | null;
    start_at?: string | null;
    end_at?: string | null;
    duration_minutes?: number | null;
    total_marks: number;
    passing_marks: number;
    negative_marking: number;
    randomize_questions: boolean;
    randomize_options: boolean;
    status: string;
    published_at?: string | null;
    questions?: AssessmentQuestion[];
}

export interface AssessmentAttempt {
    id: number;
    assessment_id: number;
    student_id: number;
    started_at?: string | null;
    submitted_at?: string | null;
    time_taken?: number | null;
    score: number;
    percentage: number;
    rank?: number | null;
    status: string;
    answers?: Array<{
        id: number;
        question_id: number;
        selected_answer: unknown[];
        is_correct: boolean | null;
        marks_awarded: number;
    }>;
}

export interface AssessmentResult {
    attempt_id: number;
    assessment_id: number;
    assessment_title: string;
    score: number;
    percentage: number;
    rank?: number | null;
    status: string;
    started_at?: string | null;
    submitted_at?: string | null;
    time_taken?: number | null;
    total_marks: number;
    passing_marks: number;
    question_count: number;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

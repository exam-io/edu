export interface ReportTemplate {
    id: number;
    name: string;
    slug: string;
    source: string;
    definition: Record<string, unknown>;
    is_active: boolean;
}

export interface ReportExecution {
    id: number;
    report_template_id: number;
    status: string;
    row_count: number;
}

export interface ReportSchedule {
    id: number;
    report_template_id: number;
    frequency: string;
    next_run_at: string;
    is_active: boolean;
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

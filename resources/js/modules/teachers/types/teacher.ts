export interface Teacher {
    id: number;
    tenant_id: number;
    user_id: number | null;
    employee_no: string;
    first_name: string;
    middle_name: string | null;
    last_name: string;
    gender: string;
    phone: string | null;
    email: string | null;
    joining_date: string;
    experience_years: number | null;
    status: string;
}

export interface TeacherEnvelope<TData> {
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

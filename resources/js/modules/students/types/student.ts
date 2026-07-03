export interface Student {
    id: number;
    tenant_id: number;
    user_id: number | null;
    admission_no: string;
    roll_no: string | null;
    first_name: string;
    middle_name: string | null;
    last_name: string;
    gender: string;
    date_of_birth: string;
    blood_group: string | null;
    phone: string | null;
    email: string | null;
    admission_date: string;
    status: string;
}

export interface StudentEnvelope<TData> {
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

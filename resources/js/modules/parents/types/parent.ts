export interface ParentProfile {
    id: number;
    tenant_id: number;
    user_id: number | null;
    first_name: string;
    last_name: string;
    relationship: string;
    phone: string;
    email: string | null;
    occupation: string | null;
    address: string | null;
    status: string;
}

export interface ParentEnvelope<TData> {
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

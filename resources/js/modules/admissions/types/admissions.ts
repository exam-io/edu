export interface AdmissionApplication {
    id: number;
    full_name: string;
    email: string;
    program?: string | null;
    status: string;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

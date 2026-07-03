export interface Department {
    id: number;
    tenant_id: number;
    name: string;
    code: string;
    description: string | null;
    status: string;
}

export interface Program {
    id: number;
    tenant_id: number;
    department_id: number;
    name: string;
    code: string;
    description: string | null;
    status: string;
}

export interface AcademicClass {
    id: number;
    tenant_id: number;
    program_id: number;
    academic_session_id: number;
    name: string;
    code: string;
    description: string | null;
    status: string;
}

export interface Section {
    id: number;
    tenant_id: number;
    class_id: number;
    name: string;
    code: string;
    capacity: number;
    status: string;
}

export interface Batch {
    id: number;
    tenant_id: number;
    class_id: number;
    name: string;
    start_date: string;
    end_date: string | null;
    capacity: number;
    status: string;
}

export interface Subject {
    id: number;
    tenant_id: number;
    department_id: number;
    name: string;
    code: string;
    description: string | null;
    credit_hours: number;
    status: string;
}

export type AcademicEntity = Department | Program | AcademicClass | Section | Batch | Subject;

export type AcademicEntityKey =
    | 'departments'
    | 'programs'
    | 'classes'
    | 'sections'
    | 'batches'
    | 'subjects';

export interface AcademicEnvelope<TData> {
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

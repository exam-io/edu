export interface Campaign {
    id: number;
    name: string;
    subject: string;
    status: string;
    channels: string[];
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

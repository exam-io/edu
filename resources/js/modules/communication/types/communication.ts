export interface Announcement {
    id: number;
    title: string;
    body: string;
    status: string;
}

export interface CommunicationHistoryItem {
    id: number;
    source_type: string;
    channel: string;
    subject: string;
    status: string;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface CalendarEvent {
    id: number;
    title: string;
    description?: string | null;
    start_at: string;
    end_at?: string | null;
    event_type: string;
    status: 'scheduled' | 'live' | 'ended' | 'cancelled';
}

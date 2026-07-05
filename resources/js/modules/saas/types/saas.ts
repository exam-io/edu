export interface UsageCounter {
    id: number;
    metric_key: string;
    period_key: string;
    counter: number;
}

export interface SaaSDashboard {
    mrr: number;
    arr: number;
    active_subscribers: number;
}

export interface SaaSEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

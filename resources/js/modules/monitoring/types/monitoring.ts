export interface MetricSnapshot {
    id: number;
    metric_key: string;
    period_key: string;
    value: number;
    meta: Record<string, unknown>;
}

export interface AlertIncident {
    id: number;
    metric_key: string;
    severity: string;
    status: string;
    triggered_at: string;
}

export interface ApiEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

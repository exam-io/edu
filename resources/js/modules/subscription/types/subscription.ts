export interface SubscriptionPlan {
    id: number;
    code: string;
    name: string;
    billing_interval: string;
    price_amount: number;
    currency: string;
    is_active: boolean;
}

export interface TenantSubscription {
    id: number;
    status: string;
    starts_at: string | null;
    renews_at: string | null;
    plan?: SubscriptionPlan;
}

export interface SubscriptionEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

export interface PaymentIntent {
    id: number;
    provider: string;
    amount: number;
    currency: string;
    status: string;
}

export interface PaymentTransaction {
    id: number;
    provider: string;
    provider_transaction_id: string;
    amount: number;
    currency: string;
    status: string;
}

export interface PaymentEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

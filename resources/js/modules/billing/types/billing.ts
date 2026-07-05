export interface InvoiceLineItem {
    id: number;
    description: string;
    quantity: number;
    unit_amount: number;
    tax_amount: number;
    total_amount: number;
}

export interface Invoice {
    id: number;
    number: string;
    status: string;
    currency: string;
    subtotal_amount: number;
    tax_amount: number;
    total_amount: number;
    line_items?: InvoiceLineItem[];
}

export interface BillingProfile {
    id: number;
    legal_name: string | null;
    email: string | null;
    currency: string;
}

export interface BillingCenter {
    latest_invoice: Invoice | null;
    outstanding_total: number;
    mrr: number;
}

export interface BillingEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
    meta?: Record<string, unknown>;
}

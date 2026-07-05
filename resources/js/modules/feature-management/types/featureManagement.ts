export interface FeatureFlag {
    id: number;
    tenant_id: number;
    feature_key: string;
    enabled: boolean;
    source: string;
}

export interface FeatureCatalogEntry {
    id: number;
    key: string;
    name: string;
    description?: string | null;
    enabled_by_default: boolean;
}

export interface FeatureEnvelope<T> {
    success: boolean;
    message?: string;
    data: T;
}

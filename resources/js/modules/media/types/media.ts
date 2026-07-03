export interface MediaAsset {
    id: number;
    original_name: string;
    mime_type: string;
    size_bytes: number;
    visibility: string;
    status: string;
}

export interface MediaEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

export interface ContentSource {
    id: number;
    title: string;
    source_type: 'upload' | 'url' | 'text';
    status: 'queued' | 'processing' | 'processed' | 'failed';
    mime_type: string | null;
    processed_at: string | null;
}

export interface ContentProcessingEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

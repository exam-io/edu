export interface AIGenerationOutput {
    id: number;
    output_type: string;
    title: string | null;
    body: string | null;
    structured_payload: Record<string, unknown>;
    model_name: string | null;
}

export interface AIGenerationRequest {
    id: number;
    content_source_id: number | null;
    generation_type: 'questions' | 'notes' | 'summary';
    status: 'queued' | 'processing' | 'completed' | 'failed';
    prompt_text: string | null;
    outputs?: AIGenerationOutput[];
}

export interface AIEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

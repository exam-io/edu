export interface ContentItem {
    id: number;
    course_id: number;
    course_section_id: number | null;
    title: string;
    content_type: string;
    status: string;
}

export interface ContentEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

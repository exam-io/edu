export interface ExamOverview {
    total_assessments: number;
    published_assessments: number;
    exam_type_breakdown: Array<{ type: string; total: number }>;
}

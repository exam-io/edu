export interface Question {
    id: number;
    stem: string;
    question_type: string;
    difficulty: string;
    sort_order: number;
}

export interface QuestionSet {
    id: number;
    title: string;
    status: string;
    question_type: string;
    total_questions: number;
    questions?: Question[];
}

export interface QuestionBankEnvelope<TData> {
    success: boolean;
    message?: string;
    data: TData;
}

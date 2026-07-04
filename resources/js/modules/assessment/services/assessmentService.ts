import axios, { AxiosError } from 'axios';
import type { ApiEnvelope, Assessment, AssessmentAttempt, AssessmentResult } from '@modules/assessment/types/assessment';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function getErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<ApiEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function unwrap<T>(envelope: ApiEnvelope<T>): T {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const assessmentService = {
    async listAssessments(params?: Record<string, unknown>): Promise<Assessment[]> {
        try {
            const response = await http.get<ApiEnvelope<Assessment[]>>('/assessments', { params });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async createAssessment(payload: Record<string, unknown>): Promise<Assessment> {
        try {
            const response = await http.post<ApiEnvelope<Assessment>>('/assessments', payload);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async publishAssessment(id: number): Promise<Assessment> {
        try {
            const response = await http.post<ApiEnvelope<Assessment>>(`/assessments/${id}/publish`);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async startAttempt(id: number): Promise<AssessmentAttempt> {
        try {
            const response = await http.post<ApiEnvelope<AssessmentAttempt>>(`/assessments/${id}/start`);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async saveAnswer(id: number, questionId: number, selectedAnswer: unknown[], markForReview = false): Promise<AssessmentAttempt> {
        try {
            const response = await http.post<ApiEnvelope<AssessmentAttempt>>(`/assessments/${id}/save-answer`, {
                question_id: questionId,
                selected_answer: selectedAnswer,
                mark_for_review: markForReview,
            });
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async submitAttempt(id: number): Promise<AssessmentAttempt> {
        try {
            const response = await http.post<ApiEnvelope<AssessmentAttempt>>(`/assessments/${id}/submit`);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },

    async getResult(id: number): Promise<AssessmentResult> {
        try {
            const response = await http.get<ApiEnvelope<AssessmentResult>>(`/assessments/${id}/result`);
            return unwrap(response.data);
        } catch (error) {
            throw new Error(getErrorMessage(error));
        }
    },
};

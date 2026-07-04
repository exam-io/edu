import axios, { AxiosError } from 'axios';
import type { QuestionBankEnvelope, QuestionSet } from '@modules/question-bank/types/questionBank';

const questionBankHttp = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

function readErrorMessage(error: unknown): string {
    if (!(error instanceof AxiosError)) {
        return 'Request failed.';
    }

    const payload = error.response?.data as Partial<QuestionBankEnvelope<unknown>> | undefined;
    return payload?.message ?? error.message ?? 'Request failed.';
}

function readData<TData>(envelope: QuestionBankEnvelope<TData>): TData {
    if (!envelope.success) {
        throw new Error(envelope.message || 'Request failed.');
    }

    return envelope.data;
}

export const questionBankService = {
    async listSets(): Promise<QuestionSet[]> {
        try {
            const response = await questionBankHttp.get<QuestionBankEnvelope<QuestionSet[]>>('/question-sets');
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async createSet(payload: Record<string, unknown>): Promise<QuestionSet> {
        try {
            const response = await questionBankHttp.post<QuestionBankEnvelope<QuestionSet>>('/question-sets', payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async showSet(id: number): Promise<QuestionSet> {
        try {
            const response = await questionBankHttp.get<QuestionBankEnvelope<QuestionSet>>(`/question-sets/${id}`);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async updateSet(id: number, payload: Record<string, unknown>): Promise<QuestionSet> {
        try {
            const response = await questionBankHttp.put<QuestionBankEnvelope<QuestionSet>>(`/question-sets/${id}`, payload);
            return readData(response.data);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },

    async deleteSet(id: number): Promise<void> {
        try {
            await questionBankHttp.delete<QuestionBankEnvelope<unknown>>(`/question-sets/${id}`);
        } catch (error) {
            throw new Error(readErrorMessage(error));
        }
    },
};

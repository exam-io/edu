import axios from 'axios';
import type { ExamOverview } from '@modules/exam/types/exam';

export const examService = {
    async overview(): Promise<ExamOverview> {
        const response = await axios.get<{ success: boolean; data: ExamOverview }>('/api/exams/overview', {
            withCredentials: true,
            headers: { Accept: 'application/json' },
        });

        return response.data.data;
    },
};

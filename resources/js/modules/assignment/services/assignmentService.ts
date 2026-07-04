import axios from 'axios';
import type { AssignmentEnvelope, AssignmentSubmission } from '@modules/assignment/types/assignment';

const http = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: { Accept: 'application/json' },
});

export const assignmentService = {
    async submit(assessmentId: number, filePath: string): Promise<AssignmentSubmission> {
        const response = await http.post<AssignmentEnvelope<AssignmentSubmission>>(`/assignments/${assessmentId}/submit`, {
            file_path: filePath,
        });

        return response.data.data;
    },

    async listSubmissions(): Promise<AssignmentSubmission[]> {
        const response = await http.get<AssignmentEnvelope<AssignmentSubmission[]>>('/assignments/submissions');
        return response.data.data;
    },
};

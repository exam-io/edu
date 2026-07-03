import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import type { TeacherAssignment } from '@modules/enrollments/types/enrollment';

interface TeacherAssignmentCrudManagerProps {
    title: string;
    description: string;
    assignments: TeacherAssignment[];
    loading: boolean;
    error: string | null;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

export function TeacherAssignmentCrudManager(props: TeacherAssignmentCrudManagerProps) {
    return (
        <AcademicCrudManager
            title={props.title}
            description={props.description}
            fields={[
                { key: 'teacher_id', label: 'Teacher ID', type: 'number', required: true },
                { key: 'academic_session_id', label: 'Academic Session ID', type: 'number', required: true },
                { key: 'class_id', label: 'Class ID', type: 'number' },
                { key: 'section_id', label: 'Section ID', type: 'number' },
                { key: 'batch_id', label: 'Batch ID', type: 'number' },
                { key: 'subject_id', label: 'Subject ID', type: 'number' },
                { key: 'start_date', label: 'Start Date', type: 'date', required: true },
                { key: 'end_date', label: 'End Date', type: 'date' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={props.assignments}
            loading={props.loading}
            error={props.error}
            getItemLabel={(item) => `Assignment #${item.id}`}
            getItemMeta={(item) => `teacher ${item.teacher_id} | session ${item.academic_session_id}`}
            onCreate={props.onCreate}
            onUpdate={props.onUpdate}
            onDelete={props.onDelete}
        />
    );
}

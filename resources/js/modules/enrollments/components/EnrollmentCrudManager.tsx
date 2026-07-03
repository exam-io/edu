import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import type { Enrollment } from '@modules/enrollments/types/enrollment';

interface EnrollmentCrudManagerProps {
    title: string;
    description: string;
    enrollments: Enrollment[];
    loading: boolean;
    error: string | null;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

export function EnrollmentCrudManager(props: EnrollmentCrudManagerProps) {
    return (
        <AcademicCrudManager
            title={props.title}
            description={props.description}
            fields={[
                { key: 'student_id', label: 'Student ID', type: 'number', required: true },
                { key: 'academic_session_id', label: 'Academic Session ID', type: 'number', required: true },
                { key: 'class_id', label: 'Class ID', type: 'number', required: true },
                { key: 'section_id', label: 'Section ID', type: 'number' },
                { key: 'batch_id', label: 'Batch ID', type: 'number' },
                { key: 'enrollment_date', label: 'Enrollment Date', type: 'date', required: true },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={props.enrollments}
            loading={props.loading}
            error={props.error}
            getItemLabel={(item) => `Enrollment #${item.id}`}
            getItemMeta={(item) => `student ${item.student_id} | class ${item.class_id} | ${item.status}`}
            onCreate={props.onCreate}
            onUpdate={props.onUpdate}
            onDelete={props.onDelete}
        />
    );
}

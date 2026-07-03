import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import type { Teacher } from '@modules/teachers/types/teacher';

interface TeacherCrudManagerProps {
    title: string;
    description: string;
    teachers: Teacher[];
    loading: boolean;
    error: string | null;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

export function TeacherCrudManager(props: TeacherCrudManagerProps) {
    return (
        <AcademicCrudManager
            title={props.title}
            description={props.description}
            fields={[
                { key: 'employee_no', label: 'Employee No', required: true },
                { key: 'first_name', label: 'First Name', required: true },
                { key: 'middle_name', label: 'Middle Name' },
                { key: 'last_name', label: 'Last Name', required: true },
                { key: 'gender', label: 'Gender', required: true },
                { key: 'joining_date', label: 'Joining Date', type: 'date', required: true },
                { key: 'phone', label: 'Phone' },
                { key: 'email', label: 'Email' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={props.teachers}
            loading={props.loading}
            error={props.error}
            getItemLabel={(item) => `${item.first_name} ${item.last_name}`}
            getItemMeta={(item) => `${item.employee_no} | ${item.status}`}
            onCreate={props.onCreate}
            onUpdate={props.onUpdate}
            onDelete={props.onDelete}
        />
    );
}

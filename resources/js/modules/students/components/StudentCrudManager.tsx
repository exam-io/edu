import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import type { Student } from '@modules/students/types/student';

interface StudentCrudManagerProps {
    title: string;
    description: string;
    students: Student[];
    loading: boolean;
    error: string | null;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

export function StudentCrudManager(props: StudentCrudManagerProps) {
    return (
        <AcademicCrudManager
            title={props.title}
            description={props.description}
            fields={[
                { key: 'admission_no', label: 'Admission No', required: true },
                { key: 'roll_no', label: 'Roll No' },
                { key: 'first_name', label: 'First Name', required: true },
                { key: 'middle_name', label: 'Middle Name' },
                { key: 'last_name', label: 'Last Name', required: true },
                { key: 'gender', label: 'Gender', required: true },
                { key: 'date_of_birth', label: 'Date of Birth', type: 'date', required: true },
                { key: 'admission_date', label: 'Admission Date', type: 'date', required: true },
                { key: 'phone', label: 'Phone' },
                { key: 'email', label: 'Email' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={props.students}
            loading={props.loading}
            error={props.error}
            getItemLabel={(item) => `${item.first_name} ${item.last_name}`}
            getItemMeta={(item) => `${item.admission_no} | ${item.status}`}
            onCreate={props.onCreate}
            onUpdate={props.onUpdate}
            onDelete={props.onDelete}
        />
    );
}

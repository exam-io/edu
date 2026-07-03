import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import type { ParentProfile } from '@modules/parents/types/parent';

interface ParentCrudManagerProps {
    title: string;
    description: string;
    parents: ParentProfile[];
    loading: boolean;
    error: string | null;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

export function ParentCrudManager(props: ParentCrudManagerProps) {
    return (
        <AcademicCrudManager
            title={props.title}
            description={props.description}
            fields={[
                { key: 'first_name', label: 'First Name', required: true },
                { key: 'last_name', label: 'Last Name', required: true },
                { key: 'relationship', label: 'Relationship', required: true },
                { key: 'phone', label: 'Phone', required: true },
                { key: 'email', label: 'Email' },
                { key: 'occupation', label: 'Occupation' },
                { key: 'address', label: 'Address', type: 'textarea' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={props.parents}
            loading={props.loading}
            error={props.error}
            getItemLabel={(item) => `${item.first_name} ${item.last_name}`}
            getItemMeta={(item) => `${item.relationship} | ${item.phone}`}
            onCreate={props.onCreate}
            onUpdate={props.onUpdate}
            onDelete={props.onDelete}
        />
    );
}

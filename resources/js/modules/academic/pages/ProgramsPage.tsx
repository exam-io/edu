import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function ProgramsPage() {
    const { programs, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Programs"
            description="Manage programs grouped under departments."
            fields={[
                { key: 'department_id', label: 'Department ID', type: 'number', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'code', label: 'Code', required: true },
                { key: 'description', label: 'Description', type: 'textarea' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={programs}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `${item.code} | department ${item.department_id} | ${item.status}`}
            onCreate={async (payload) => {
                await create('programs', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('programs', id, payload);
            }}
            onDelete={async (id) => {
                await remove('programs', id);
            }}
        />
    );
}

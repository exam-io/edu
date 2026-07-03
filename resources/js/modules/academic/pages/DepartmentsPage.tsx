import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function DepartmentsPage() {
    const { departments, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Departments"
            description="Manage tenant academic departments."
            fields={[
                { key: 'name', label: 'Name', required: true },
                { key: 'code', label: 'Code', required: true },
                { key: 'description', label: 'Description', type: 'textarea' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={departments}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `${item.code} | ${item.status}`}
            onCreate={async (payload) => {
                await create('departments', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('departments', id, payload);
            }}
            onDelete={async (id) => {
                await remove('departments', id);
            }}
        />
    );
}

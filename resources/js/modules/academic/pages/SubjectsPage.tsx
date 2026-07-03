import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function SubjectsPage() {
    const { subjects, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Subjects"
            description="Manage subjects and credit structure under departments."
            fields={[
                { key: 'department_id', label: 'Department ID', type: 'number', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'code', label: 'Code', required: true },
                { key: 'description', label: 'Description', type: 'textarea' },
                { key: 'credit_hours', label: 'Credit Hours', type: 'number', required: true },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={subjects}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `${item.code} | department ${item.department_id} | credits ${item.credit_hours}`}
            onCreate={async (payload) => {
                await create('subjects', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('subjects', id, payload);
            }}
            onDelete={async (id) => {
                await remove('subjects', id);
            }}
        />
    );
}

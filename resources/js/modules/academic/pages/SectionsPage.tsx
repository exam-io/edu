import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function SectionsPage() {
    const { sections, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Sections"
            description="Manage sections within classes."
            fields={[
                { key: 'class_id', label: 'Class ID', type: 'number', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'code', label: 'Code', required: true },
                { key: 'capacity', label: 'Capacity', type: 'number', required: true },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={sections}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `${item.code} | class ${item.class_id} | capacity ${item.capacity}`}
            onCreate={async (payload) => {
                await create('sections', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('sections', id, payload);
            }}
            onDelete={async (id) => {
                await remove('sections', id);
            }}
        />
    );
}

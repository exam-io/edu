import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function BatchesPage() {
    const { batches, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Batches"
            description="Manage class batches and active periods."
            fields={[
                { key: 'class_id', label: 'Class ID', type: 'number', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'start_date', label: 'Start Date', type: 'date', required: true },
                { key: 'end_date', label: 'End Date', type: 'date' },
                { key: 'capacity', label: 'Capacity', type: 'number', required: true },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={batches}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `class ${item.class_id} | ${item.start_date} - ${item.end_date ?? 'open'}`}
            onCreate={async (payload) => {
                await create('batches', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('batches', id, payload);
            }}
            onDelete={async (id) => {
                await remove('batches', id);
            }}
        />
    );
}

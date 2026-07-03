import { AcademicCrudManager } from '@modules/academic/components/AcademicCrudManager';
import { useAcademic } from '@modules/academic/hooks/useAcademic';

export function ClassesPage() {
    const { classes, create, update, delete: remove, loading, error } = useAcademic();

    return (
        <AcademicCrudManager
            title="Classes"
            description="Manage classes under programs and academic sessions."
            fields={[
                { key: 'program_id', label: 'Program ID', type: 'number', required: true },
                { key: 'academic_session_id', label: 'Academic Session ID', type: 'number', required: true },
                { key: 'name', label: 'Name', required: true },
                { key: 'code', label: 'Code', required: true },
                { key: 'description', label: 'Description', type: 'textarea' },
                { key: 'status', label: 'Status', placeholder: 'active' },
            ]}
            items={classes}
            loading={loading}
            error={error}
            getItemLabel={(item) => item.name}
            getItemMeta={(item) => `${item.code} | program ${item.program_id} | session ${item.academic_session_id}`}
            onCreate={async (payload) => {
                await create('classes', payload);
            }}
            onUpdate={async (id, payload) => {
                await update('classes', id, payload);
            }}
            onDelete={async (id) => {
                await remove('classes', id);
            }}
        />
    );
}

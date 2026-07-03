import { ParentCrudManager } from '@modules/parents/components/ParentCrudManager';
import { useParents } from '@modules/parents/hooks/useParents';
import { useEffect } from 'react';

export function ParentsListPage() {
    const { parents, loading, error, createParent, updateParent, deleteParent, initialize } = useParents();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <ParentCrudManager
            title="Parents"
            description="Manage parent profiles and contacts."
            parents={parents}
            loading={loading}
            error={error}
            onCreate={async (payload) => {
                await createParent(payload);
            }}
            onUpdate={async (id, payload) => {
                await updateParent(id, payload);
            }}
            onDelete={async (id) => {
                await deleteParent(id);
            }}
        />
    );
}

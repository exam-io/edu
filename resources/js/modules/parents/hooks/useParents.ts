import { useParentStore } from '@modules/parents/store/parentStore';

export function useParents() {
    const parents = useParentStore((state) => state.parents);
    const selectedParent = useParentStore((state) => state.selectedParent);
    const loading = useParentStore((state) => state.loading);
    const initialized = useParentStore((state) => state.initialized);
    const error = useParentStore((state) => state.error);
    const loadParents = useParentStore((state) => state.loadParents);
    const selectParent = useParentStore((state) => state.selectParent);
    const createParent = useParentStore((state) => state.createParent);
    const updateParent = useParentStore((state) => state.updateParent);
    const deleteParent = useParentStore((state) => state.deleteParent);
    const initialize = useParentStore((state) => state.initialize);

    return {
        parents,
        selectedParent,
        loading,
        initialized,
        error,
        loadParents,
        selectParent,
        createParent,
        updateParent,
        deleteParent,
        initialize,
    };
}

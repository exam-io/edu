import { create } from 'zustand';
import { parentService } from '@modules/parents/services/parentService';
import type { ParentProfile } from '@modules/parents/types/parent';

interface ParentState {
    parents: ParentProfile[];
    selectedParent: ParentProfile | null;
    loading: boolean;
    initialized: boolean;
    error: string | null;
    loadParents: () => Promise<void>;
    selectParent: (parent: ParentProfile | null) => void;
    createParent: (payload: Record<string, unknown>) => Promise<ParentProfile>;
    updateParent: (id: number, payload: Record<string, unknown>) => Promise<ParentProfile>;
    deleteParent: (id: number) => Promise<void>;
    initialize: () => Promise<void>;
}

export const useParentStore = create<ParentState>((set, get) => ({
    parents: [],
    selectedParent: null,
    loading: false,
    initialized: false,
    error: null,

    loadParents: async () => {
        try {
            const parents = await parentService.list();
            set({ parents, error: null });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load parents.' });
        }
    },

    selectParent: (parent) => set({ selectedParent: parent }),

    createParent: async (payload) => {
        const created = await parentService.create(payload);
        set((state) => ({ parents: [created, ...state.parents], error: null }));
        return created;
    },

    updateParent: async (id, payload) => {
        const updated = await parentService.update(id, payload);
        set((state) => ({
            parents: state.parents.map((item) => (item.id === id ? updated : item)),
            selectedParent: state.selectedParent?.id === id ? updated : state.selectedParent,
            error: null,
        }));
        return updated;
    },

    deleteParent: async (id) => {
        await parentService.delete(id);
        set((state) => ({
            parents: state.parents.filter((item) => item.id !== id),
            selectedParent: state.selectedParent?.id === id ? null : state.selectedParent,
            error: null,
        }));
    },

    initialize: async () => {
        if (get().initialized) {
            return;
        }

        set({ loading: true, error: null });
        try {
            await get().loadParents();
            set({ initialized: true });
        } finally {
            set({ loading: false });
        }
    },
}));

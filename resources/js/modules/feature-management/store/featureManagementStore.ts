import { create } from 'zustand';
import { featureManagementService } from '@modules/feature-management/services/featureManagementService';
import type { FeatureCatalogEntry, FeatureFlag } from '@modules/feature-management/types/featureManagement';

interface FeatureManagementState {
    catalog: FeatureCatalogEntry[];
    flags: FeatureFlag[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    toggle: (featureKey: string, enabled: boolean) => Promise<void>;
}

export const useFeatureManagementStore = create<FeatureManagementState>((set, get) => ({
    catalog: [],
    flags: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [catalog, flags] = await Promise.all([
                featureManagementService.catalog(),
                featureManagementService.flags(),
            ]);
            set({ catalog, flags });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load features.' });
        } finally {
            set({ loading: false });
        }
    },

    toggle: async (featureKey, enabled) => {
        set({ loading: true, error: null });
        try {
            const current = get().flags;
            const nextMap = new Map(current.map((flag) => [flag.feature_key, flag]));
            nextMap.set(featureKey, {
                id: nextMap.get(featureKey)?.id ?? 0,
                tenant_id: nextMap.get(featureKey)?.tenant_id ?? 0,
                feature_key: featureKey,
                enabled,
                source: 'manual',
            });

            const payload = Array.from(nextMap.values()).map((flag) => ({
                feature_key: flag.feature_key,
                enabled: flag.enabled,
                source: 'manual',
            }));

            const flags = await featureManagementService.update(payload);
            set({ flags });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to update features.' });
        } finally {
            set({ loading: false });
        }
    },
}));

import { create } from 'zustand';
import { mobileProvisioningService } from '@modules/mobile-provisioning/services/mobileProvisioningService';
import type { MobileBuildRequest, MobileConfig } from '@modules/mobile-provisioning/types/mobileProvisioning';

interface MobileProvisioningState {
    config: MobileConfig | null;
    requests: MobileBuildRequest[];
    loading: boolean;
    error: string | null;
    initialize: () => Promise<void>;
    publish: () => Promise<void>;
}

export const useMobileProvisioningStore = create<MobileProvisioningState>((set) => ({
    config: null,
    requests: [],
    loading: false,
    error: null,

    initialize: async () => {
        set({ loading: true, error: null });
        try {
            const [config, requests] = await Promise.all([
                mobileProvisioningService.config(),
                mobileProvisioningService.requests(),
            ]);
            set({ config, requests });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to load mobile provisioning state.' });
        } finally {
            set({ loading: false });
        }
    },

    publish: async () => {
        set({ loading: true, error: null });
        try {
            const config = await mobileProvisioningService.publish();
            set({ config });
        } catch (error) {
            set({ error: error instanceof Error ? error.message : 'Failed to publish mobile config.' });
        } finally {
            set({ loading: false });
        }
    },
}));

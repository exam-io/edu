import { useEffect } from 'react';
import { useFeatureManagementStore } from '@modules/feature-management/store/featureManagementStore';

export function FeatureFlagsPage() {
    const { catalog, flags, loading, error, initialize, toggle } = useFeatureManagementStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Feature Flags</h1>
                <p className="text-sm text-gray-500">Control tenant-level capabilities safely.</p>
            </header>
            <div className="grid gap-3 max-w-2xl">
                {catalog.map((entry) => {
                    const flag = flags.find((item) => item.feature_key === entry.key);
                    const enabled = flag ? flag.enabled : entry.enabled_by_default;

                    return (
                        <label className="flex items-center justify-between rounded border p-3" key={entry.key}>
                            <div>
                                <p className="font-medium">{entry.name}</p>
                                <p className="text-xs text-gray-500">{entry.key}</p>
                            </div>
                            <input
                                checked={enabled}
                                disabled={loading}
                                onChange={(event) => {
                                    void toggle(entry.key, event.target.checked);
                                }}
                                type="checkbox"
                            />
                        </label>
                    );
                })}
                {error ? <p className="text-sm text-red-600">{error}</p> : null}
            </div>
        </section>
    );
}

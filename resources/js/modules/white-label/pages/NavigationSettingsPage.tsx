import { useEffect } from 'react';
import { useWhiteLabelStore } from '@modules/white-label/store/whiteLabelStore';

export function NavigationSettingsPage() {
    const { navigation, initialize } = useWhiteLabelStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Navigation Configuration</h1>
                <p className="text-sm text-gray-500">Review tenant-specific navigation and feature-gated items.</p>
            </header>
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Version: {navigation?.version ?? 0}</p>
                <ul className="mt-3 grid gap-2">
                    {(navigation?.items ?? []).map((item) => (
                        <li className="rounded border p-2" key={item.key}>
                            <p className="font-medium">{item.label}</p>
                            <p className="text-xs text-gray-500">{item.path}</p>
                        </li>
                    ))}
                </ul>
            </div>
        </section>
    );
}

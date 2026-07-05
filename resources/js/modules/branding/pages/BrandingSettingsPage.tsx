import { FormEvent, useEffect } from 'react';
import { useBrandingStore } from '@modules/branding/store/brandingStore';

export function BrandingSettingsPage() {
    const { profile, loading, error, initialize, save } = useBrandingStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        const formData = new FormData(event.currentTarget);
        await save({
            name: String(formData.get('name') ?? ''),
            primary_color: String(formData.get('primary_color') ?? '#0b6eff'),
            secondary_color: String(formData.get('secondary_color') ?? '#00a889'),
        });
    };

    return (
        <section className="space-y-6">
            <header>
                <h1 className="text-2xl font-semibold">Branding Settings</h1>
                <p className="text-sm text-gray-500">Manage your white-label colors and identity.</p>
            </header>
            <form className="grid gap-4 max-w-2xl" key={profile?.id ?? 0} onSubmit={onSubmit}>
                <input className="rounded border p-2" defaultValue={profile?.name ?? ''} name="name" placeholder="Brand name" />
                <div className="flex gap-3">
                    <input className="h-10 w-20" defaultValue={profile?.primary_color ?? '#0b6eff'} name="primary_color" type="color" />
                    <input className="h-10 w-20" defaultValue={profile?.secondary_color ?? '#00a889'} name="secondary_color" type="color" />
                </div>
                <button className="rounded bg-blue-600 px-4 py-2 text-white" disabled={loading} type="submit">
                    {loading ? 'Saving...' : 'Save Branding'}
                </button>
                {error ? <p className="text-sm text-red-600">{error}</p> : null}
            </form>
        </section>
    );
}

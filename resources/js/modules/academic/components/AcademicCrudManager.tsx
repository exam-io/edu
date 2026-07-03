import { type ChangeEvent, useMemo, useState } from 'react';

type FieldType = 'text' | 'textarea' | 'number' | 'date';

export interface AcademicCrudField {
    key: string;
    label: string;
    type?: FieldType;
    required?: boolean;
    placeholder?: string;
}

interface AcademicCrudManagerProps<TItem extends { id: number }> {
    title: string;
    description: string;
    fields: AcademicCrudField[];
    items: TItem[];
    loading: boolean;
    error: string | null;
    getItemLabel: (item: TItem) => string;
    getItemMeta: (item: TItem) => string;
    onCreate: (payload: Record<string, unknown>) => Promise<void>;
    onUpdate: (id: number, payload: Record<string, unknown>) => Promise<void>;
    onDelete: (id: number) => Promise<void>;
}

function getInitialValues(fields: AcademicCrudField[]): Record<string, string> {
    const values: Record<string, string> = {};

    for (const field of fields) {
        values[field.key] = '';
    }

    return values;
}

function toPayload(values: Record<string, string>, fields: AcademicCrudField[]): Record<string, unknown> {
    const payload: Record<string, unknown> = {};

    for (const field of fields) {
        const value = values[field.key]?.trim() ?? '';

        if (value === '') {
            continue;
        }

        if (field.type === 'number') {
            payload[field.key] = Number(value);
            continue;
        }

        payload[field.key] = value;
    }

    return payload;
}

export function AcademicCrudManager<TItem extends { id: number }>({
    title,
    description,
    fields,
    items,
    loading,
    error,
    getItemLabel,
    getItemMeta,
    onCreate,
    onUpdate,
    onDelete,
}: AcademicCrudManagerProps<TItem>) {
    const [mode, setMode] = useState<'create' | 'edit'>('create');
    const [activeId, setActiveId] = useState<number | null>(null);
    const [values, setValues] = useState<Record<string, string>>(() => getInitialValues(fields));
    const [search, setSearch] = useState('');

    const filteredItems = useMemo(() => {
        if (search.trim() === '') {
            return items;
        }

        const q = search.trim().toLowerCase();

        return items.filter((item) => {
            const label = getItemLabel(item).toLowerCase();
            const meta = getItemMeta(item).toLowerCase();

            return label.includes(q) || meta.includes(q);
        });
    }, [items, search, getItemLabel, getItemMeta]);

    const resetForm = () => {
        setMode('create');
        setActiveId(null);
        setValues(getInitialValues(fields));
    };

    const editItem = (item: TItem) => {
        const nextValues = getInitialValues(fields);

        for (const field of fields) {
            const rawValue = (item as Record<string, unknown>)[field.key];
            nextValues[field.key] = rawValue === null || rawValue === undefined ? '' : String(rawValue);
        }

        setMode('edit');
        setActiveId(item.id);
        setValues(nextValues);
    };

    const submit = async () => {
        const payload = toPayload(values, fields);

        if (mode === 'edit' && activeId !== null) {
            await onUpdate(activeId, payload);
            resetForm();
            return;
        }

        await onCreate(payload);
        resetForm();
    };

    return (
        <div className="mx-auto max-w-6xl space-y-6 p-6">
            <header className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h1 className="text-xl font-semibold text-slate-900">{title}</h1>
                <p className="mt-1 text-sm text-slate-600">{description}</p>
            </header>

            <section className="grid gap-6 lg:grid-cols-[1.2fr_1fr]">
                <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div className="mb-4 flex items-center justify-between gap-4">
                        <h2 className="text-base font-semibold text-slate-900">Records</h2>
                        <input
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Search by name or code"
                            className="w-full max-w-xs rounded-lg border border-slate-300 px-3 py-2 text-sm"
                        />
                    </div>

                    <div className="space-y-3">
                        {filteredItems.map((item) => (
                            <article key={item.id} className="rounded-xl border border-slate-200 p-4">
                                <p className="text-sm font-semibold text-slate-900">{getItemLabel(item)}</p>
                                <p className="mt-1 text-xs text-slate-500">{getItemMeta(item)}</p>
                                <div className="mt-3 flex gap-2">
                                    <button
                                        type="button"
                                        onClick={() => editItem(item)}
                                        className="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => {
                                            void onDelete(item.id);
                                        }}
                                        className="rounded-md border border-rose-300 px-3 py-1.5 text-xs font-medium text-rose-700"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </article>
                        ))}

                        {filteredItems.length === 0 ? (
                            <div className="rounded-xl border border-dashed border-slate-300 p-4 text-sm text-slate-500">
                                No records found.
                            </div>
                        ) : null}
                    </div>
                </div>

                <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div className="mb-4 flex items-center justify-between">
                        <h2 className="text-base font-semibold text-slate-900">
                            {mode === 'edit' ? 'Update Record' : 'Create Record'}
                        </h2>
                        {mode === 'edit' ? (
                            <button
                                type="button"
                                onClick={resetForm}
                                className="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700"
                            >
                                Cancel Edit
                            </button>
                        ) : null}
                    </div>

                    <div className="space-y-3">
                        {fields.map((field) => {
                            const commonProps = {
                                value: values[field.key] ?? '',
                                onChange: (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
                                    setValues((state) => ({
                                        ...state,
                                        [field.key]: event.target.value,
                                    }));
                                },
                                placeholder: field.placeholder ?? field.label,
                                className: 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm',
                                required: field.required,
                            };

                            if (field.type === 'textarea') {
                                return (
                                    <label key={field.key} className="block">
                                        <span className="mb-1 block text-xs font-medium text-slate-600">{field.label}</span>
                                        <textarea rows={3} {...commonProps} />
                                    </label>
                                );
                            }

                            return (
                                <label key={field.key} className="block">
                                    <span className="mb-1 block text-xs font-medium text-slate-600">{field.label}</span>
                                    <input type={field.type ?? 'text'} {...commonProps} />
                                </label>
                            );
                        })}

                        {error ? <p className="text-xs text-rose-600">{error}</p> : null}

                        <button
                            type="button"
                            onClick={() => {
                                void submit();
                            }}
                            disabled={loading}
                            className="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white disabled:opacity-60"
                        >
                            {loading ? 'Saving...' : mode === 'edit' ? 'Update' : 'Create'}
                        </button>
                    </div>
                </div>
            </section>
        </div>
    );
}

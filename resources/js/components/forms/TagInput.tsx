import { useState } from 'react';

interface TagInputProps {
    label: string;
    value: string[];
    onChange: (value: string[]) => void;
}

export function TagInput({ label, value, onChange }: TagInputProps) {
    const [draft, setDraft] = useState('');

    function addTag() {
        const tag = draft.trim();

        if (!tag || value.includes(tag)) {
            setDraft('');
            return;
        }

        onChange([...value, tag]);
        setDraft('');
    }

    return (
        <div className="grid gap-2">
            <label className="text-sm font-medium">{label}</label>
            <div className="flex gap-2">
                <input
                    value={draft}
                    onChange={(event) => setDraft(event.target.value)}
                    onKeyDown={(event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            addTag();
                        }
                    }}
                    className="w-full rounded-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-sm"
                    placeholder="Add tag"
                />
                <button type="button" className="btn-ghost" onClick={addTag}>
                    Add
                </button>
            </div>
            <div className="flex flex-wrap gap-2">
                {value.map((tag) => (
                    <button
                        key={tag}
                        type="button"
                        className="rounded-full border border-[var(--color-border)] bg-[var(--color-surface-alt)] px-2 py-1 text-xs"
                        onClick={() => onChange(value.filter((item) => item !== tag))}
                    >
                        {tag} x
                    </button>
                ))}
            </div>
        </div>
    );
}

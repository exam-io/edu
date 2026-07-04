import { Bold, Italic, List } from 'lucide-react';
import { useRef } from 'react';

interface RichTextEditorProps {
    label: string;
    value: string;
    onChange: (value: string) => void;
}

export function RichTextEditor({ label, value, onChange }: RichTextEditorProps) {
    const ref = useRef<HTMLDivElement | null>(null);

    function run(command: 'bold' | 'italic' | 'insertUnorderedList') {
        document.execCommand(command);
        onChange(ref.current?.innerHTML ?? '');
    }

    return (
        <section className="grid gap-2">
            <p className="text-sm font-medium">{label}</p>
            <div className="flex items-center gap-1 rounded-t-[var(--radius-input)] border border-[var(--color-border)] border-b-0 bg-[var(--color-surface-alt)] p-2">
                <button type="button" className="btn-ghost" onClick={() => run('bold')} aria-label="Bold">
                    <Bold size={14} />
                </button>
                <button type="button" className="btn-ghost" onClick={() => run('italic')} aria-label="Italic">
                    <Italic size={14} />
                </button>
                <button type="button" className="btn-ghost" onClick={() => run('insertUnorderedList')} aria-label="Bullet list">
                    <List size={14} />
                </button>
            </div>
            <div
                ref={ref}
                contentEditable
                suppressContentEditableWarning
                className="min-h-32 rounded-b-[var(--radius-input)] border border-[var(--color-border)] bg-[var(--color-surface)] p-3 text-sm outline-none"
                onInput={(event) => onChange((event.target as HTMLDivElement).innerHTML)}
                dangerouslySetInnerHTML={{ __html: value }}
            />
        </section>
    );
}

import { UploadCloud } from 'lucide-react';
import { useMemo, useRef, useState, type DragEvent } from 'react';

interface FileDropzoneProps {
    title: string;
    onFilesSelected?: (files: File[]) => void;
    accept?: string;
}

export function FileDropzone({ title, onFilesSelected, accept }: FileDropzoneProps) {
    const [dragActive, setDragActive] = useState(false);
    const inputRef = useRef<HTMLInputElement | null>(null);

    const classes = useMemo(
        () =>
            `rounded-[var(--radius-card)] border border-dashed p-6 text-center transition ${
                dragActive
                    ? 'border-[var(--color-primary)] bg-[color-mix(in_oklab,var(--color-primary)_8%,transparent)]'
                    : 'border-[var(--color-border)] bg-[var(--color-surface)] hover:border-[var(--color-primary)]'
            }`,
        [dragActive],
    );

    function handleSelection(files: FileList | null) {
        if (!files) {
            return;
        }
        onFilesSelected?.(Array.from(files));
    }

    function onDrop(event: DragEvent<HTMLDivElement>) {
        event.preventDefault();
        setDragActive(false);
        handleSelection(event.dataTransfer.files);
    }

    return (
        <div
            role="button"
            tabIndex={0}
            className={classes}
            onDragOver={(event) => {
                event.preventDefault();
                setDragActive(true);
            }}
            onDragLeave={() => setDragActive(false)}
            onDrop={onDrop}
            onClick={() => inputRef.current?.click()}
            onKeyDown={(event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    inputRef.current?.click();
                }
            }}
        >
            <input
                ref={inputRef}
                type="file"
                className="hidden"
                accept={accept}
                onChange={(event) => handleSelection(event.target.files)}
            />
            <div className="mx-auto flex max-w-sm flex-col items-center gap-3">
                <span className="rounded-2xl bg-[var(--color-surface-alt)] p-3 text-[var(--color-primary)]">
                    <UploadCloud size={18} />
                </span>
                <p className="text-sm font-semibold">{title}</p>
                <p className="text-xs text-[var(--color-muted)]">Drag and drop or click to upload</p>
            </div>
        </div>
    );
}

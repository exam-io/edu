import { Bot } from 'lucide-react';
import type { ReactNode } from 'react';

interface AIUploadCardProps {
    title: string;
    description: string;
    icon?: ReactNode;
    dropzone: ReactNode;
}

export function AIUploadCard({ title, description, icon, dropzone }: AIUploadCardProps) {
    return (
        <article className="rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] p-5 shadow-[var(--shadow-soft)]">
            <header className="mb-4 flex items-start gap-3">
                <span className="rounded-xl bg-[var(--color-surface-alt)] p-2 text-[var(--color-primary)]">{icon ?? <Bot size={16} />}</span>
                <div>
                    <h3 className="text-sm font-semibold">{title}</h3>
                    <p className="mt-1 text-sm text-[var(--color-muted)]">{description}</p>
                </div>
            </header>
            {dropzone}
        </article>
    );
}

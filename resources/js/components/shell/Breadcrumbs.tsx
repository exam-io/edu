import { ChevronRight } from 'lucide-react';
import { Link, useLocation } from 'react-router-dom';

export function Breadcrumbs() {
    const location = useLocation();
    const segments = location.pathname.split('/').filter(Boolean);

    return (
        <nav aria-label="Breadcrumb" className="flex flex-wrap items-center gap-1 text-xs text-[var(--color-muted)]">
            <Link to="/" className="rounded px-1 py-0.5 hover:bg-[var(--color-surface-alt)] hover:text-[var(--color-text)]">
                Home
            </Link>
            {segments.map((segment, index) => {
                const href = `/${segments.slice(0, index + 1).join('/')}`;
                const label = segment.replace(/-/g, ' ');

                return (
                    <span key={href} className="flex items-center gap-1">
                        <ChevronRight size={12} />
                        <Link to={href} className="rounded px-1 py-0.5 capitalize hover:bg-[var(--color-surface-alt)] hover:text-[var(--color-text)]">
                            {label}
                        </Link>
                    </span>
                );
            })}
        </nav>
    );
}

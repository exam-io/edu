import { ChevronLeft, ChevronRight, Download, SlidersHorizontal } from 'lucide-react';
import type { ReactNode } from 'react';

interface Column<T> {
    key: string;
    header: string;
    cell: (row: T) => ReactNode;
}

interface DataTableProps<T> {
    title: string;
    rows: T[];
    columns: Column<T>[];
    emptyState?: ReactNode;
    loading?: boolean;
}

export function DataTable<T>({ title, rows, columns, emptyState, loading = false }: DataTableProps<T>) {
    return (
        <section className="overflow-hidden rounded-[var(--radius-card)] border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[var(--shadow-soft)]">
            <header className="flex items-center justify-between border-b border-[var(--color-border)] px-4 py-3">
                <h3 className="text-sm font-semibold">{title}</h3>
                <div className="flex items-center gap-2">
                    <button type="button" className="btn-ghost">
                        <SlidersHorizontal size={14} />
                        Columns
                    </button>
                    <button type="button" className="btn-ghost">
                        <Download size={14} />
                        Export
                    </button>
                </div>
            </header>

            <div className="max-w-full overflow-x-auto">
                <table className="w-full min-w-[640px] text-left text-sm">
                    <thead className="sticky top-0 bg-[var(--color-surface-alt)] text-xs uppercase tracking-[0.12em] text-[var(--color-muted)]">
                        <tr>
                            {columns.map((column) => (
                                <th key={column.key} className="px-4 py-3 font-semibold">
                                    {column.header}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {loading
                            ? Array.from({ length: 6 }).map((_, index) => (
                                  <tr key={`skeleton-${index}`} className="border-t border-[var(--color-border)]">
                                      {columns.map((column) => (
                                          <td key={`${column.key}-${index}`} className="px-4 py-3">
                                              <span className="skeleton h-4 w-full max-w-[140px]" />
                                          </td>
                                      ))}
                                  </tr>
                              ))
                            : rows.map((row, rowIndex) => (
                                  <tr key={`row-${rowIndex}`} className="border-t border-[var(--color-border)] transition hover:bg-[var(--color-surface-alt)]">
                                      {columns.map((column) => (
                                          <td key={`${column.key}-${rowIndex}`} className="px-4 py-3">
                                              {column.cell(row)}
                                          </td>
                                      ))}
                                  </tr>
                              ))}
                    </tbody>
                </table>
            </div>

            {!loading && rows.length === 0 ? <div className="p-6">{emptyState}</div> : null}

            <footer className="flex items-center justify-between border-t border-[var(--color-border)] px-4 py-3 text-xs text-[var(--color-muted)]">
                <p>Showing {Math.min(rows.length, 10)} rows</p>
                <div className="flex items-center gap-2">
                    <button type="button" className="btn-ghost" aria-label="Previous page">
                        <ChevronLeft size={14} />
                    </button>
                    <span className="rounded-full border border-[var(--color-border)] px-2 py-1">1</span>
                    <button type="button" className="btn-ghost" aria-label="Next page">
                        <ChevronRight size={14} />
                    </button>
                </div>
            </footer>
        </section>
    );
}

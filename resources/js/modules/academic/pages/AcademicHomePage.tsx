import { Link } from 'react-router-dom';

const links = [
    { label: 'Departments', path: '/academic/departments' },
    { label: 'Programs', path: '/academic/programs' },
    { label: 'Classes', path: '/academic/classes' },
    { label: 'Sections', path: '/academic/sections' },
    { label: 'Batches', path: '/academic/batches' },
    { label: 'Subjects', path: '/academic/subjects' },
];

export function AcademicHomePage() {
    return (
        <div className="mx-auto max-w-5xl space-y-6 p-6">
            <header className="rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-900 to-slate-700 p-6 text-white">
                <h1 className="text-2xl font-semibold">Academic Structure Foundation</h1>
                <p className="mt-2 text-sm text-slate-200">Manage core hierarchy: departments, programs, classes, sections, batches, and subjects.</p>
            </header>

            <section className="grid gap-4 md:grid-cols-3">
                {links.map((link) => (
                    <Link
                        key={link.path}
                        to={link.path}
                        className="rounded-xl border border-slate-200 bg-white p-4 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300"
                    >
                        {link.label}
                    </Link>
                ))}
            </section>
        </div>
    );
}

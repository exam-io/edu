export function AssessmentInstructionsPage() {
    return (
        <section className="space-y-3 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <h2 className="text-xl font-semibold">Assessment Instructions</h2>
            <ul className="list-disc pl-5 text-sm text-[var(--color-muted)]">
                <li>Review all instructions before starting.</li>
                <li>Use save and next to preserve progress.</li>
                <li>Marked-for-review answers can be revisited before submit.</li>
                <li>Auto-save runs periodically during attempt.</li>
            </ul>
        </section>
    );
}

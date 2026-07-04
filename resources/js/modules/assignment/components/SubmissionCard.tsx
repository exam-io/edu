import type { AssignmentSubmission } from '@modules/assignment/types/assignment';

interface SubmissionCardProps {
    submission: AssignmentSubmission;
}

export function SubmissionCard({ submission }: SubmissionCardProps) {
    return (
        <article className="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] p-4">
            <p className="font-medium">Submission #{submission.id}</p>
            <p className="text-xs text-[var(--color-muted)]">Assessment: {submission.assessment_id} | Status: {submission.status}</p>
            <p className="mt-1 text-xs">{submission.file_path}</p>
        </article>
    );
}

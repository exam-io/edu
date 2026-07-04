import { AssessmentBuilder } from '@modules/assessment/components/AssessmentBuilder';

export function AssessmentBuilderPage() {
    return (
        <section className="space-y-4">
            <h2 className="text-xl font-semibold">Assessment Builder</h2>
            <AssessmentBuilder />
        </section>
    );
}

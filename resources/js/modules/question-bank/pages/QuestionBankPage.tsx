import { useEffect } from 'react';
import { useQuestionBankStore } from '@modules/question-bank/store/questionBankStore';

export function QuestionBankPage() {
    const { sets, loading, error, initialize } = useQuestionBankStore();

    useEffect(() => {
        void initialize();
    }, [initialize]);

    return (
        <section className="space-y-4">
            <h1 className="text-2xl font-semibold">Question Bank</h1>
            {loading ? <p>Loading question sets...</p> : null}
            {error ? <p className="text-red-600">{error}</p> : null}
            <div className="rounded border p-4">
                <p className="text-sm text-gray-500">Total question sets: {sets.length}</p>
            </div>
        </section>
    );
}

import { LiveClassCard } from '@components/cards/LiveClassCard';

export function LiveClassesPage() {
    return (
        <section className="space-y-4">
            <header>
                <h2 className="text-xl font-semibold">Live Classes</h2>
                <p className="text-sm text-[var(--color-muted)]">Join classes instantly, monitor attendance, and access resources or chat.</p>
            </header>

            <div className="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                <LiveClassCard subject="Grade 9 Biology" teacher="Dr. Sharma" attendees={36} schedule="09:00 - 09:45" status="live" />
                <LiveClassCard subject="Grade 8 Mathematics" teacher="Ms. Kaur" attendees={29} schedule="10:00 - 10:45" status="upcoming" />
                <LiveClassCard subject="Grade 11 Chemistry" teacher="Mr. Das" attendees={33} schedule="11:30 - 12:15" status="upcoming" />
                <LiveClassCard subject="Grade 10 English" teacher="Ms. Fernandes" attendees={31} schedule="01:00 - 01:45" status="ended" />
                <LiveClassCard subject="Grade 12 Physics" teacher="Dr. Menon" attendees={28} schedule="02:00 - 02:45" status="live" />
                <LiveClassCard subject="Grade 7 Computer Science" teacher="Mr. Roy" attendees={42} schedule="03:00 - 03:45" status="upcoming" />
            </div>
        </section>
    );
}

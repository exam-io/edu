import { useMemo } from 'react';
import { useParents } from '@modules/parents/hooks/useParents';

export function ParentProfilePage() {
    const { selectedParent } = useParents();
    const fullName = useMemo(() => {
        if (!selectedParent) {
            return 'No parent selected';
        }

        return `${selectedParent.first_name} ${selectedParent.last_name}`;
    }, [selectedParent]);

    return (
        <section>
            <h2>Parent Profile</h2>
            <p>{fullName}</p>
        </section>
    );
}

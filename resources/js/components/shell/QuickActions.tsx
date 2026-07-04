import { Plus } from 'lucide-react';
import { Link } from 'react-router-dom';

interface QuickAction {
    label: string;
    path: string;
}

interface QuickActionsProps {
    actions: QuickAction[];
}

export function QuickActions({ actions }: QuickActionsProps) {
    return (
        <div className="flex flex-wrap items-center gap-2">
            {actions.map((action) => (
                <Link key={action.path} to={action.path} className="btn-primary text-xs">
                    <Plus size={14} />
                    {action.label}
                </Link>
            ))}
        </div>
    );
}

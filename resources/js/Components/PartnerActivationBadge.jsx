import { Badge } from '@/Components/ui/badge';
import { cn } from '@/lib/utils';

export default function PartnerActivationBadge({ activatedAt }) {
    return (
        <Badge
            variant="outline"
            className={cn(
                'text-xs font-medium',
                activatedAt
                    ? 'border-green-200 bg-green-50 text-green-700 hover:bg-green-50'
                    : 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-50',
            )}
        >
            {activatedAt ? 'Activo' : 'Pendiente de activación'}
        </Badge>
    );
}

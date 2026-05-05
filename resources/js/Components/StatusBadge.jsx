import { Badge } from '@/Components/ui/badge';
import { cn } from '@/lib/utils';

const STATUS_STYLES = {
    'Pendiente de Respuesta Del Partner':
        'border-yellow-200 bg-yellow-50 text-yellow-700 hover:bg-yellow-50',
    'Respondido Por El Partner':
        'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-50',
    'Demorado - Sin Respuesta Del Partner (48h)':
        'border-orange-200 bg-orange-50 text-[#FF7500] hover:bg-orange-50',
    'Cerrado - Sin Respuesta Del Partner':
        'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-50',
    'Cerrado - Sin Respuesta Del Usuario':
        'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-50',
    'Cerrado Por El Partner':
        'border-green-200 bg-green-50 text-green-700 hover:bg-green-50',
};

const DEFAULT_STYLE = 'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-50';

export default function StatusBadge({ status }) {
    const style = STATUS_STYLES[status] ?? DEFAULT_STYLE;

    return (
        <Badge variant="outline" className={cn('text-xs font-medium', style)}>
            {status ?? 'Sin estado'}
        </Badge>
    );
}

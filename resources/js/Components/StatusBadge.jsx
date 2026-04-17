/**
 * Badge de color según el estado del FormSubmission.
 * Props:
 *   status — string con el nombre del estado
 */
const STATUS_STYLES = {
    'Pendiente de Respuesta Del Partner': 'bg-yellow-100 text-yellow-800',
    'Respondido Por El Partner': 'bg-blue-100 text-blue-800',
    'Demorado - Sin Respuesta Del Partner (48h)': 'bg-orange-100 text-orange-800',
    'Cerrado - Sin Respuesta Del Partner': 'bg-gray-100 text-gray-600',
    'Cerrado - Sin Respuesta Del Usuario': 'bg-gray-100 text-gray-600',
    'Cerrado Por El Partner': 'bg-green-100 text-green-800',
};

const DEFAULT_STYLE = 'bg-gray-100 text-gray-600';

export default function StatusBadge({ status }) {
    const style = STATUS_STYLES[status] ?? DEFAULT_STYLE;

    return (
        <span
            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${style}`}
        >
            {status ?? 'Sin estado'}
        </span>
    );
}

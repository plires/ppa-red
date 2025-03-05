@php
    function statusColorClass($status)
    {
        switch ($status) {
            case App\Models\FormSubmissionStatus::STATUS_PENDIENTE_RTA_DE_PARTNER:
                return 'bg-primary';
            case App\Models\FormSubmissionStatus::STATUS_RESPONDIO_PARTNER:
                return 'bg-success';
            case App\Models\FormSubmissionStatus::STATUS_DEMORADO_POR_PARTNER:
                return 'bg-danger';
            case App\Models\FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_PARTNER:
                return 'bg-warning';
            case App\Models\FormSubmissionStatus::STATUS_CERRADO_SIN_RTA_USUARIO:
                return 'bg-secondary';
            case App\Models\FormSubmissionStatus::STATUS_CERRADO_SIN_MAS_ACTIVIDAD:
                return 'bg-dark';
            case App\Models\FormSubmissionStatus::STATUS_CERRADO_POR_EL_PARTNER:
                return 'bg-dark';
            default:
                return 'bg-primary'; // Estado por defecto
        }
    }
@endphp

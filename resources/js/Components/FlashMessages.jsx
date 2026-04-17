import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { CheckCircle, XCircle, X } from 'lucide-react';

export default function FlashMessages() {
    const { flash } = usePage().props;
    const [visible, setVisible] = useState({ success: true, error: true });

    useEffect(() => {
        setVisible({ success: true, error: true });
    }, [flash]);

    if (!flash?.success && !flash?.error) return null;

    return (
        <div className="px-6 pt-4 space-y-2">
            {flash?.success && visible.success && (
                <div className="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-green-600" />
                    <p className="flex-1">{flash.success}</p>
                    <button
                        onClick={() => setVisible((v) => ({ ...v, success: false }))}
                        className="text-green-500 hover:text-green-700"
                    >
                        <X className="h-4 w-4" />
                    </button>
                </div>
            )}
            {flash?.error && visible.error && (
                <div className="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <XCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-red-600" />
                    <p className="flex-1">{flash.error}</p>
                    <button
                        onClick={() => setVisible((v) => ({ ...v, error: false }))}
                        className="text-red-500 hover:text-red-700"
                    >
                        <X className="h-4 w-4" />
                    </button>
                </div>
            )}
        </div>
    );
}

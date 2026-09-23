import { Head } from '@inertiajs/react'
import { Mail } from 'lucide-react'
import { useState } from 'react'

export default function MailPreview({ templates }) {
  const [selected, setSelected] = useState(templates[0]?.key ?? null)

  return (
    <>
      <Head title='Preview de emails' />

      <div className='flex h-screen w-screen bg-gray-100'>
        <aside className='flex w-72 flex-shrink-0 flex-col border-r border-gray-200 bg-white'>
          <div className='flex items-center gap-2 border-b border-gray-200 px-5 py-4'>
            <Mail className='h-5 w-5 text-[#FF7500]' />
            <div>
              <h1 className='text-sm font-semibold text-gray-800'>
                Preview de emails
              </h1>
              <p className='text-xs text-gray-400'>Solo disponible en local</p>
            </div>
          </div>

          <nav className='flex-1 overflow-y-auto py-2'>
            {templates.map(template => (
              <button
                key={template.key}
                onClick={() => setSelected(template.key)}
                className={`block w-full border-l-4 px-5 py-3 text-left text-sm transition ${
                  selected === template.key
                    ? 'border-[#FF7500] bg-orange-50 font-medium text-gray-900'
                    : 'border-transparent text-gray-600 hover:bg-gray-50'
                }`}
              >
                {template.label}
              </button>
            ))}
          </nav>
        </aside>

        <main className='flex-1 bg-gray-200 p-6'>
          {selected ? (
            <iframe
              key={selected}
              title={selected}
              src={route('dev.mail_preview.render', selected)}
              className='h-full w-full rounded-lg border border-gray-300 bg-white shadow-sm'
            />
          ) : (
            <p className='text-sm text-gray-500'>
              No hay plantillas para mostrar.
            </p>
          )}
        </main>
      </div>
    </>
  )
}

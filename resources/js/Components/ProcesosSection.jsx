import { useState, useEffect } from 'react'
import styles from './ProcesosSection.module.css'
import icon1 from '../../images/landing/1.webp'
import icon2 from '../../images/landing/2.webp'
import icon3 from '../../images/landing/3.webp'
import icon4 from '../../images/landing/4.webp'
import punto from '../../images/landing/punto.webp'

const STEPS = [
  { icon: icon1, line1: 'Recibimos', line2: 'tu solicitud.' },
  { icon: icon2, line1: 'Contacto', line2: 'en 24 hs.' },
  { icon: icon3, line1: 'Instalación', line2: 'profesional.' },
  { icon: icon4, line1: 'Seguimiento', line2: 'automático.' },
]

export default function ProcesosSection() {
  const [active, setActive] = useState(0)

  useEffect(() => {
    const timer = setInterval(() => setActive(prev => (prev + 1) % 4), 3000)
    return () => clearInterval(timer)
  }, [])

  return (
    <section className={styles.section}>
      <div className={styles.card}>
        <p className={styles.heading}>
          Instaladores oficiales en tu zona.
          <br />
          Proceso transparente y certificado por PPA.
        </p>

        {/* ── Mobile: 2×2 grid ── */}
        <div className={`grid grid-cols-2 gap-6 md:hidden`}>
          {STEPS.map((step, i) => (
            <div
              key={i}
              className={`${styles.mobileStep} ${i === active ? styles.stepOn : styles.stepOff}`}
            >
              <img
                src={step.icon}
                alt={`Paso ${i + 1}`}
                className={styles.mobileIcon}
              />
              <img
                src={punto}
                alt=''
                aria-hidden='true'
                className={`${styles.mobileDot} ${i === active ? styles.dotOn : styles.dotOff}`}
              />
              <p className={styles.mobileLabel}>
                {step.line1}
                <br />
                {step.line2}
              </p>
            </div>
          ))}
        </div>

        {/* ── Desktop: 3 filas separadas ── */}
        <div className='hidden md:block'>
          {/* Fila 1: iconos + chevrons */}
          <div className={styles.iconsRow}>
            {STEPS.map((step, i) => (
              <div key={i} className={styles.iconUnit}>
                <img
                  src={step.icon}
                  alt={`Paso ${i + 1}`}
                  className={`${styles.desktopIcon} ${i === active ? styles.stepOn : styles.stepOff}`}
                />
              </div>
            ))}
          </div>

          {/* Fila 2: línea timeline + puntos */}
          <div className={styles.timeline}>
            <span className={styles.timelineLine} aria-hidden='true' />
            {STEPS.map((_, i) => (
              <img
                key={i}
                src={punto}
                alt=''
                aria-hidden='true'
                className={`${styles.timelineDot} ${i === active ? styles.dotOn : styles.dotOff}`}
              />
            ))}
          </div>

          {/* Fila 3: labels */}
          <div className={`grid grid-cols-4`}>
            {STEPS.map((step, i) => (
              <p
                key={i}
                className={`${styles.desktopLabel} ${i === active ? styles.labelOn : styles.labelOff}`}
              >
                {step.line1}
                <br />
                {step.line2}
              </p>
            ))}
          </div>
        </div>
      </div>
    </section>
  )
}

import styles from './FeaturesSection.module.css'
import iconTx from '../../images/landing/tx.svg'
import iconCandado from '../../images/landing/candado.svg'
import iconReloj from '../../images/landing/reloj.svg'

const FEATURES = [
  {
    icon: iconTx,
    alt: 'Control remoto PPA',
    title: 'Cero exposición',
    text: 'Abrís y cerrás desde el auto.',
  },
  {
    icon: iconReloj,
    alt: 'Apertura rápida',
    title: 'Rapidez que protege',
    text: 'Apertura y cierre en segundos.',
  },
  {
    icon: iconCandado,
    alt: 'Seguridad total',
    title: 'Control total',
    text: 'Sabés siempre si tu portón está cerrado.',
  },
]

export default function FeaturesSection() {
  return (
    <>
      <section className={`${styles.features} features`}>
        <div className={styles.featuresInner}>
          <header className={styles.header}>
            <h2 className={styles.title}>No más bajarte del auto.</h2>
            <p className={styles.subtitle}>
              Con un portón PPA llegás y salís sin exponerte. Evitá situaciones
              de riesgo y ganá comodidad en tus entradas.
            </p>
          </header>

          <ul
            className={`${styles.list} grid grid-cols-1 sm:grid-cols-3 gap-4`}
            role='list'
          >
            {FEATURES.map(f => (
              <li key={f.title} className={`${styles.item} m-auto`}>
                <div className={styles.iconWrap} aria-hidden='true'>
                  <img src={f.icon} alt='' className={styles.icon} />
                </div>
                <h3 className={styles.itemTitle}>{f.title}</h3>
                <p className={styles.itemText}>{f.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>
    </>
  )
}

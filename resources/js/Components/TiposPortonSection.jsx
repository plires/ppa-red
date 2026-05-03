import styles from './TiposPortonSection.module.css'
import corredizos from '../../images/landing/corredizos.webp'
import levadizos from '../../images/landing/levadizos.webp'
import pivotantes from '../../images/landing/pivotantes.webp'

const TIPOS = [
  {
    src: corredizos,
    alt: 'Motor para portón corredizo',
    label: 'Corredizos',
    desc: 'Perfecto para entradas con poco espacio.',
  },
  {
    src: levadizos,
    alt: 'Motor para portón levadizo',
    label: 'Levadizos',
    desc: 'Apertura vertical, ideal para cocheras.',
  },
  {
    src: pivotantes,
    alt: 'Motor para portón pivotante',
    label: 'Pivotantes',
    desc: 'Diseño clásico con tecnología moderna.',
  },
]

export default function TiposPortonSection() {
  return (
    <section className={styles.section}>
      <h2 className={styles.title}>
        Automatizamos
        <br />
        cualquier tipo de portón
      </h2>

      <ul className={styles.grid} role='list'>
        {TIPOS.map(({ src, alt, label, desc }) => (
          <li key={label} className={styles.item}>
            <img src={src} alt={alt} className={styles.image} />
            <h3 className={styles.label}>{label}</h3>
            <p className={styles.desc}>{desc}</p>
          </li>
        ))}
      </ul>
    </section>
  )
}

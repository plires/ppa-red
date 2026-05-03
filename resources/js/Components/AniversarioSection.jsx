import styles from './AniversarioSection.module.css'
import treintaAnos from '../../images/landing/30-anos.webp'

const BADGES = ['Tecnología propia', 'Red de instaladores', 'Miles de clientes']

export default function AniversarioSection() {
  return (
    <section className={styles.section}>
      <div className={styles.inner}>
        <div className={styles.frame}>
          <img
            src={treintaAnos}
            alt='30 años automatizando Argentina'
            className={styles.image}
          />
        </div>

        <p className={styles.subtitle}>AÑOS AUTOMATIZANDO ARGENTINA</p>

        <ul
          className={`${styles.badges} flex flex-wrap justify-center gap-3`}
          role='list'
        >
          {BADGES.map(badge => (
            <li key={badge} className={styles.badge}>
              {badge}
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}

import styles from './AniversarioSection.module.css'
import treintaAnos from '../../images/landing/30-anos.webp'

const BADGES = ['Tecnología propia', 'Red de instaladores', 'Miles de clientes']

export default function AniversarioSection() {
  return (
    <section className={styles.section}>
      <div className={styles.inner}>
        <div className={styles.frame} data-aos='zoom-in' data-aos-duration='800'>
          <img
            src={treintaAnos}
            alt='30 años automatizando Argentina'
            className={styles.image}
          />
        </div>

        <p className={styles.subtitle} data-aos='fade-up' data-aos-delay='150'>AÑOS AUTOMATIZANDO ARGENTINA</p>

        <ul
          className={`${styles.badges} flex flex-wrap justify-center gap-3`}
          role='list'
        >
          {BADGES.map((badge, i) => (
            <li
              key={badge}
              className={styles.badge}
              data-aos='fade-up'
              data-aos-delay={250 + i * 100}
            >
              {badge}
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}

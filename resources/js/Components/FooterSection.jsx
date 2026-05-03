import styles from './FooterSection.module.css'
import logoPpa from '../../images/landing/logo-ppa.webp'

export default function FooterSection() {
  return (
    <footer className={styles.footer}>
      <div className={styles.inner}>
        <img src={logoPpa} alt='PPA' className={styles.logo} />

        <p className={styles.brand}>ARGENTINA</p>
        <p className={styles.importador}>IMPORTADOR OFICIAL</p>

        <p className={styles.tagline}>30 años automatizando Argentina.</p>

        <div className={styles.contact}>
          <p>
            WhatsApp:{' '}
            <a href='https://wa.me/5491136643484' className={styles.link}>
              +54 9 11 3664 3484
            </a>
          </p>
          <p>
            <a href='mailto:contacto@ppared.com.ar' className={styles.link}>
              contacto@ppared.com.ar
            </a>
          </p>
        </div>
      </div>
    </footer>
  )
}

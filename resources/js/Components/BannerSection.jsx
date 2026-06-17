import styles from './BannerSection.module.css'
import autoLluvia from '../../images/landing/auto2-lluvia.webp'
import autoLluviaMobile from '../../images/landing/auto2-lluvia-mobile.webp'

export default function BannerSection() {
  return (
    <>
      <section className={`${styles.banner} banner`}>
        <div className={styles.bannerInner}>
          <div className={styles.bannerImageWrap}>
            <picture>
              {/* Imagen para Desktop (de 768px en adelante) */}
              <source media='(min-width: 768px)' srcSet={autoLluvia} />

              {/* Imagen por defecto para Móvil (menor a 768px) */}
              <img
                src={autoLluviaMobile}
                alt='Auto llegando bajo la lluvia con portón PPA automático'
                className={styles.bannerBg}
              />
            </picture>
          </div>
          <div className={styles.bannerOverlay} aria-hidden='true' />
          <div className={styles.bannerContent}>
            <h2
              className={styles.bannerTitle}
              data-aos='fade-right'
              data-aos-duration='800'
            >
              LA COMODIDAD
              <br />
              QUE TE CAMBIA
              <br /> EL DÍA.
            </h2>
            <p
              className={styles.bannerBody}
              data-aos='fade-right'
              data-aos-delay='180'
              data-aos-duration='800'
            >
              Llegás y el portón <strong>se abre con un click.</strong>
              <br />
              Ideal para compras, lluvia y llegadas nocturnas.
            </p>
          </div>
        </div>
      </section>
    </>
  )
}

import styles from './BannerSection.module.css'
import iconTx from '../../images/landing/tx.svg'
import iconCandado from '../../images/landing/candado.svg'
import iconReloj from '../../images/landing/reloj.svg'
import autoLluvia from '../../images/landing/auto-lluvia.webp'
import autoLluviaMobile from '../../images/landing/auto-lluvia-mobile.webp'

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
            <h2 className={styles.bannerTitle}>
              LA COMODIDAD
              <br />
              QUE TE CAMBIA
              <br /> EL DÍA.
            </h2>
            <p className={styles.bannerBody}>
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

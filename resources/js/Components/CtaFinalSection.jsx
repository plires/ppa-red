import styles from './CtaFinalSection.module.css'

function scrollToPresupuesto(e) {
  e.preventDefault()
  document
    .getElementById('presupuesto')
    ?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

export default function CtaFinalSection() {
  return (
    <section className={styles.section}>
      <div className={styles.card}>
        <h2 className={styles.title}>
          Dale a tu familia la seguridad que merece.
        </h2>
        <p className={styles.subtitle}>
          Instalación rápida, tecnología confiable y respaldo de 30 años.
        </p>
        <a
          href='#presupuesto'
          className={styles.btn}
          onClick={scrollToPresupuesto}
        >
          AUTOMATIZAR MI PORTÓN AHORA
        </a>
      </div>
    </section>
  )
}

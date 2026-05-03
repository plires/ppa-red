import styles from './CtaPresupuestoSection.module.css'

function scrollToPresupuesto(e) {
  e.preventDefault()
  const target = document.getElementById('presupuesto')
  if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

export default function CtaPresupuestoSection() {
  return (
    <section className={styles.section}>
      <div className={styles.inner}>
        <div className={styles.copy}>
          <h2 className={styles.title}>
            Conseguí tu presupuesto
            <br />
            personalizado GRATIS
          </h2>
          <p className={styles.subtitle}>
            En 2 minutos sabés cuánto cuesta.
            <br />
            Completá el formulario y tenelo en 24hs.
          </p>
        </div>

        <a href='#presupuesto' className={styles.btn} onClick={scrollToPresupuesto}>
          QUIERO MI PRESUPUESTO GRATIS
        </a>
      </div>
    </section>
  )
}

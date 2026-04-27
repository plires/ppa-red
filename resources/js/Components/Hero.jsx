import styles from './Hero.module.css'
import heroBg from '../../images/landing/hero/hero-background.webp'
import logoPpaRed from '../../images/landing/hero/logo-ppa-red.svg'

const BENEFITS = [
  'Instalación en 24-48hs.',
  '1 Año de Garantía.',
  'Instaladores oficiales.',
]

function FormCard({
  data,
  setData,
  errors,
  processing,
  provinces,
  zones,
  localities,
  loadingZones,
  loadingLocalities,
  handleProvinceChange,
  handleZoneChange,
  onSubmit,
}) {
  return (
    <div className={styles.formCard}>
      <h2 className={styles.formTitle}>
        Pedí tu presupuesto <br />
        ¡GRATIS!
      </h2>
      <p className={styles.formSubtitle}>
        Completá los datos y un instalador oficial te contacta en 24hs.
      </p>

      <form onSubmit={onSubmit}>
        <div className={styles.field}>
          <input
            type='text'
            className={styles.input}
            placeholder='Nombre completo'
            value={data.name}
            onChange={e => setData('name', e.target.value)}
          />
          {errors.name && <p className={styles.error}>{errors.name}</p>}
        </div>

        <div className={styles.field}>
          <input
            type='tel'
            className={styles.input}
            placeholder='Teléfono / WhatsApp'
            value={data.phone}
            onChange={e => setData('phone', e.target.value)}
          />
          {errors.phone && <p className={styles.error}>{errors.phone}</p>}
        </div>

        <div className={styles.field}>
          <input
            type='email'
            className={styles.input}
            placeholder='Email (opcional)'
            value={data.email}
            onChange={e => setData('email', e.target.value)}
          />
          {errors.email && <p className={styles.error}>{errors.email}</p>}
        </div>

        <div className={styles.field}>
          <select
            className={styles.select}
            value={data.province_id}
            onChange={handleProvinceChange}
          >
            <option value=''>
              {provinces.length === 0 ? 'Cargando...' : 'Provincia / Ciudad'}
            </option>
            {provinces.map(p => (
              <option key={p.id} value={p.id}>
                {p.name}
              </option>
            ))}
          </select>
          {errors.province_id && (
            <p className={styles.error}>{errors.province_id}</p>
          )}
        </div>

        {(zones.length > 0 || loadingZones) && (
          <div className={styles.field}>
            <select
              className={styles.select}
              value={data.zone_id}
              onChange={handleZoneChange}
              disabled={loadingZones}
            >
              <option value=''>
                {loadingZones ? 'Cargando zonas...' : 'Seleccioná una zona'}
              </option>
              {zones.map(z => (
                <option key={z.id} value={z.id}>
                  {z.name}
                </option>
              ))}
            </select>
            {errors.zone_id && <p className={styles.error}>{errors.zone_id}</p>}
          </div>
        )}

        {data.province_id && (
          <div className={styles.field}>
            <select
              className={styles.select}
              value={data.locality_id}
              onChange={e => setData('locality_id', e.target.value)}
              disabled={loadingLocalities || localities.length === 0}
            >
              <option value=''>
                {loadingLocalities
                  ? 'Cargando localidades...'
                  : localities.length === 0
                    ? zones.length > 0 && !data.zone_id
                      ? 'Primero seleccioná una zona'
                      : 'Sin localidades disponibles'
                    : 'Seleccioná una localidad'}
              </option>
              {localities.map(l => (
                <option key={l.id} value={l.id}>
                  {l.name}
                </option>
              ))}
            </select>
            {errors.locality_id && (
              <p className={styles.error}>{errors.locality_id}</p>
            )}
          </div>
        )}

        <div className={styles.field}>
          <textarea
            className={styles.textarea}
            placeholder='Comentarios (opcional)'
            value={data.message}
            onChange={e => setData('message', e.target.value)}
            rows={3}
          />
          {errors.message && <p className={styles.error}>{errors.message}</p>}
        </div>

        <button
          type='submit'
          disabled={processing}
          className={styles.submitBtn}
        >
          {processing ? 'Enviando...' : 'QUIERO MI PRESUPUESTO GRATIS'}
        </button>
      </form>
    </div>
  )
}

function BadgeList() {
  return (
    <>
      {BENEFITS.map(benefit => (
        <div key={benefit} className={styles.badge}>
          <span className={styles.badgeIcon}>✓</span>
          <span>{benefit}</span>
        </div>
      ))}
    </>
  )
}

export default function Hero(props) {
  return (
    <>
      {/* ── MOBILE ── */}
      <div className={styles.mobileBrand}>
        <img src={logoPpaRed} alt='PPA RED' className={styles.logo} />
        <h1 className={styles.headline}>
          Automatizá tu portón con la confianza de 30 años de experiencia.
        </h1>
        <p className={styles.subtext}>
          <strong>PPA Red</strong> te conecta con instaladores oficiales en todo
          el país.
        </p>
      </div>
      <div className={styles.mobileForm}>
        <FormCard {...props} />
      </div>

      {/* ── DESKTOP ── */}
      <div className={styles.desktop}>
        {/* Foto hero con diagonal naranja encima */}
        <div
          className={styles.heroBg}
          style={{ backgroundImage: `url(${heroBg})` }}
        >
          <div className={styles.diagonal} aria-hidden='true' />
          <div className={styles.brand}>
            <img src={logoPpaRed} alt='PPA RED' className={styles.logo} />
            <h1 className={styles.headline}>
              Automatizá tu portón con la confianza de 30 años de experiencia.
            </h1>
            <p className={styles.subtext}>
              <strong>PPA Red</strong> te conecta con instaladores oficiales en
              todo el país.
            </p>
          </div>
        </div>

        {/*
                  Fila de contenido:
                  - margin-top negativo sube el form sobre el borde inferior de la foto
                  - fondo blanco cubre el área debajo de la foto
                  - badges tienen padding-top para aparecer debajo de la foto
                */}
        <div className={styles.contentRow}>
          <div className={styles.formCol}>
            <FormCard {...props} />
          </div>
          <div className={styles.badgesCol}>
            <BadgeList />
          </div>
        </div>
      </div>
    </>
  )
}

import { useState, useEffect, useCallback } from 'react'
import useEmblaCarousel from 'embla-carousel-react'
import Autoplay from 'embla-carousel-autoplay'
import styles from './TestimoniosSection.module.css'
import userIcon from '../../images/landing/user.svg'

const TESTIMONIOS = [
  {
    nombre: 'María, Belgrano.',
    estrellas: 5,
    texto:
      'Increíble el cambio en mi día a día. El instalador llegó puntual y dejó todo perfecto.',
  },
  {
    nombre: 'Carlos, Quilmes.',
    estrellas: 5,
    texto:
      'El motor PPA lleva 3 años sin problemas. La calidad del equipo es notoria desde el primer día.',
  },
  {
    nombre: 'Ana, Córdoba.',
    estrellas: 5,
    texto:
      'Excelente seguimiento y comunicación. Me llamaron a las pocas horas de completar el formulario.',
  },
  {
    nombre: 'Roberto, Rosario.',
    estrellas: 4,
    texto:
      'Muy buen servicio. El instalador explicó todo el funcionamiento y fue muy amable. Lo recomiendo.',
  },
  {
    nombre: 'Sofía, Mendoza.',
    estrellas: 5,
    texto:
      'Solicité el presupuesto y me contactaron en menos de una hora. Rapidez y profesionalismo total.',
  },
  {
    nombre: 'Diego, Mar del Plata.',
    estrellas: 4,
    texto:
      'Llamé por un service y vinieron al día siguiente. La garantía funcionó sin drama, resolvieron todo.',
  },
  {
    nombre: 'Valentina, Tucumán.',
    estrellas: 5,
    texto:
      'Tenía miedo de que no llegaran hasta acá. Llegaron puntual, instalaron rápido y el trato fue excelente.',
  },
  {
    nombre: 'Marcelo, San Isidro.',
    estrellas: 3,
    texto:
      'Buen equipo y buena calidad. El contacto inicial fue ágil. Le doy 3 estrellas porque tardaron un poco más de lo esperado.',
  },
  {
    nombre: 'Laura, Salta.',
    estrellas: 5,
    texto:
      'El motor funciona perfecto hace dos años. Cuando tuve una duda me atendieron de inmediato. 100% recomendable.',
  },
  {
    nombre: 'Gustavo, La Plata.',
    estrellas: 4,
    texto:
      'Pedí un service y me sorprendió la rapidez del contacto. En 24 horas ya tenía turno confirmado.',
  },
  {
    nombre: 'Natalia, Neuquén.',
    estrellas: 5,
    texto:
      'Instalación impecable. El técnico fue muy prolijo y explicativo. El portón quedó funcionando perfecto.',
  },
  {
    nombre: 'Fernando, Santa Fe.',
    estrellas: 4,
    texto:
      'Excelente relación calidad-precio. La garantía cubre todo y el equipo de soporte responde rápido.',
  },
]

function Estrellas({ cantidad }) {
  return (
    <div className={styles.estrellas} aria-label={`${cantidad} de 5 estrellas`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <span key={i} className={i < cantidad ? styles.starOn : styles.starOff}>
          ★
        </span>
      ))}
    </div>
  )
}

export default function TestimoniosSection() {
  const [emblaRef, emblaApi] = useEmblaCarousel(
    { loop: true, align: 'center', dragFree: true },
    [Autoplay({ delay: 3500, stopOnInteraction: true })],
  )

  const [selectedIndex, setSelectedIndex] = useState(0)
  const [scrollSnaps, setScrollSnaps] = useState([])

  const onSelect = useCallback(() => {
    if (!emblaApi) return
    setSelectedIndex(emblaApi.selectedScrollSnap())
  }, [emblaApi])

  useEffect(() => {
    if (!emblaApi) return
    setScrollSnaps(emblaApi.scrollSnapList())
    emblaApi.on('select', onSelect)
    emblaApi.on('reInit', () => setScrollSnaps(emblaApi.scrollSnapList()))
    return () => emblaApi.off('select', onSelect)
  }, [emblaApi, onSelect])

  function scrollTo(index) {
    emblaApi?.scrollTo(index)
  }

  function handleMouseEnter() {
    emblaApi?.plugins()?.autoplay?.stop()
  }

  function handleMouseLeave() {
    emblaApi?.plugins()?.autoplay?.play()
  }

  return (
    <section className={styles.section}>
      <h2 className={styles.title}>Lo que dicen nuestros clientes.</h2>

      <div
        className={styles.viewport}
        ref={emblaRef}
        onMouseEnter={handleMouseEnter}
        onMouseLeave={handleMouseLeave}
      >
        <div className={styles.container}>
          {TESTIMONIOS.map(({ nombre, estrellas, texto }) => (
            <div key={nombre} className={styles.slide}>
              <img
                src={userIcon}
                alt=''
                className={styles.avatar}
                aria-hidden='true'
              />
              <Estrellas cantidad={estrellas} />
              <p className={styles.texto}>{texto}</p>
              <p className={styles.nombre}>{nombre}</p>
            </div>
          ))}
        </div>
      </div>

      <div className={styles.dots} role='tablist' aria-label='Testimonios'>
        {scrollSnaps.map((_, i) => (
          <button
            key={i}
            type='button'
            role='tab'
            aria-selected={i === selectedIndex}
            aria-label={`Ir al testimonio ${i + 1}`}
            className={`${styles.dot} ${i === selectedIndex ? styles.dotActive : ''}`}
            onClick={() => scrollTo(i)}
          />
        ))}
      </div>
    </section>
  )
}

import { useState } from 'react'
import styles from './FaqSection.module.css'

const FAQS = [
  {
    pregunta: '¿Cuánto cuesta automatizar mi portón?',
    respuesta:
      'El precio varía según el tipo de portón (corredizo, levadizo o pivotante) y el modelo de motor elegido. Completá el formulario y recibís un presupuesto personalizado gratis en menos de 24 horas.',
  },
  {
    pregunta: '¿En cuánto tiempo lo instalan?',
    respuesta:
      'Una vez aprobado el presupuesto, la instalación se coordina dentro de las 24 a 48 horas hábiles. El trabajo se realiza generalmente en el día.',
  },
  {
    pregunta: '¿Qué garantía tiene?',
    respuesta:
      '1 año de garantía oficial ISP + servicio técnico especializado. Cubrimos piezas y mano de obra ante cualquier falla de fábrica durante ese período.',
  },
  {
    pregunta: '¿Atienden en todo el país?',
    respuesta:
      'Sí. Contamos con una red de instaladores oficiales en todas las provincias de Argentina. Al completar el formulario asignamos el técnico más cercano a tu domicilio.',
  },
  {
    pregunta: '¿Qué pasa si el motor se rompe fuera de garantía?',
    respuesta:
      'Ofrecemos servicio técnico post-garantía. Podés solicitar un service en cualquier momento; nuestros técnicos diagnostican el problema y te envían un presupuesto de reparación sin cargo.',
  },
  {
    pregunta: '¿El motor funciona ante cortes de luz?',
    respuesta:
      'Todos los modelos incluyen desbloqueo manual que permiten operar el portón durante cortes de energía.',
  },
  {
    pregunta: '¿Puedo instalar el motor en un portón existente?',
    respuesta:
      'En la mayoría de los casos sí. Nuestros técnicos evalúan el portón en el momento de la visita y determinan si requiere alguna adaptación menor. El presupuesto incluye esa evaluación sin costo adicional.',
  },
  {
    pregunta: '¿Cuántos controles remotos se incluyen?',
    respuesta:
      'Los equipos incluyen 2 controles remotos. Podés adquirir controles adicionales.',
  },
  {
    pregunta: '¿Qué diferencia hay entre un motor corredizo y uno levadizo?',
    respuesta:
      'El motor corredizo mueve el portón de forma lateral y es ideal para entradas con poco espacio frontal. El levadizo eleva el portón hacia arriba y es más común en cocheras con techo inclinado. Nuestros técnicos te asesoran según tu caso.',
  },
  {
    pregunta: '¿Cómo solicito un service para un equipo ya instalado?',
    respuesta:
      'Completá el formulario de contacto indicando en los comentarios que es un pedido de service. Un técnico te contacta en menos de 24 horas para coordinar la visita.',
  },
  {
    pregunta: '¿Tienen modelos de alta velocidad?',
    respuesta:
      'Sí, ofrecemos modelos con apertura rápida que reducen el tiempo de apertura a la mitad. Consultanos por disponibilidad y precios.',
  },
]

export default function FaqSection() {
  const [openIndex, setOpenIndex] = useState(0)

  function toggle(i) {
    setOpenIndex(prev => (prev === i ? -1 : i))
  }

  return (
    <section className={styles.section}>
      <div className={styles.inner}>
        <h2 className={styles.title}>Preguntas frecuentes:</h2>

        <ul className={styles.list} role='list'>
          {FAQS.map(({ pregunta, respuesta }, i) => {
            const isOpen = openIndex === i
            return (
              <li key={i} className={styles.item}>
                <button
                  type='button'
                  className={styles.trigger}
                  aria-expanded={isOpen}
                  onClick={() => toggle(i)}
                >
                  <span className={styles.question}>{pregunta}</span>
                  <span className={styles.icon} aria-hidden='true'>
                    {isOpen ? '−' : '+'}
                  </span>
                </button>

                {isOpen && <p className={styles.answer}>{respuesta}</p>}
              </li>
            )
          })}
        </ul>
      </div>
    </section>
  )
}

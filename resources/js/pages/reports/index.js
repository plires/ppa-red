import Chart from 'chart.js/auto'
import { resetTable, getStartAndEndDate } from '@/utils/utils'
import { loadFormSubmissionsTableBase } from '@/utils/loaders'

document.addEventListener('DOMContentLoaded', function () {
  const routeChartData = window.routeChartData

  const startDate = document.getElementById('start_date')
  const endDate = document.getElementById('end_date')
  const partnerId = document.getElementById('partner_id')

  let chart = null // Variable global para el gráfico
  const ctx = document.getElementById('chartFormsByPartner').getContext('2d')

  function createChart(data) {
    if (chart) {
      chart.destroy() // Destruir el gráfico existente antes de crear uno nuevo
    }

    chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        id: data.id,
        datasets: [
          {
            label: 'Cantidad de Formularios',
            data: data.data,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            min: 0,
          },
        },
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index
            const partnerId = chart.data.id[index]
            loadFormSubmissionsTable(partnerId)
          }
        },
      },
    })
  }

  function fetchChartData() {
    const { start, end } = getStartAndEndDate(startDate, endDate)

    const params = new URLSearchParams({
      start_date: start,
      end_date: end,
      partner_id: partnerId.value,
    })

    resetTable('#tableFormSubmissions') // Llamamos a la función de limpieza

    fetch(`${routeChartData}?${params.toString()}`)
      .then(response => response.json())
      .then(data => {
        createChart(data) // Llamamos a la función para crear el gráfico con los nuevos datos
      })
  }

  function loadFormSubmissionsTable(partnerId) {
    const url = `/reports/form_submissions/${partnerId}`
    loadFormSubmissionsTableBase({
      url,
      startDate,
      endDate,
      rowRenderer: f => {
        let data = JSON.parse(f.data)
        return [
          `<a href="/form_submissions/${f.id}">${data.name}</a>`,
          `<a href="/form_submissions/${f.id}">${data.email}</a>`,
          `<a href="/form_submissions/${f.id}">${data.phone}</a>`,
          `<a href="/form_submissions/${f.id}">${f.locality?.name || 'N/A'}</a>`,
          `<a href="/form_submissions/${f.id}">${new Date(f.created_at).toLocaleDateString()}</a>`,
        ]
      },
    })
  }

  // Cargar gráfico inicial sin filtros
  fetchChartData()

  // Aplicar filtros al cambiar los inputs
  document
    .getElementById('start_date')
    .addEventListener('change', fetchChartData)
  document.getElementById('end_date').addEventListener('change', fetchChartData)
  document
    .getElementById('partner_id')
    .addEventListener('change', fetchChartData)
})

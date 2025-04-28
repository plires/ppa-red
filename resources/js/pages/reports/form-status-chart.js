import Chart from 'chart.js/auto'
import { resetTable, getStartAndEndDate } from '@/js/utils/utils'
import { loadFormSubmissionsTableBase } from '@/js/utils/loaders'

document.addEventListener('DOMContentLoaded', function () {
  const statuses = window.statuses || {}
  const routeChartData = window.routeChartData

  const startDate = document.getElementById('start_date')
  const endDate = document.getElementById('end_date')
  const partnerId = document.getElementById('partner_id')

  let chart = null
  const ctx = document.getElementById('chartFormStatus').getContext('2d')

  function createChart(data, backgroundColors) {
    if (chart) chart.destroy()
    chart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: data.labels,
        id: data.id,
        datasets: [
          {
            label: 'Cantidad de Formularios',
            data: data.data,
            backgroundColor: backgroundColors,
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const index = elements[0].index
            const statusId = data.status_ids[index]
            const partner_id = partnerId.value === '' ? null : partnerId.value
            loadFormSubmissionsTable(partner_id, statusId)
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

    resetTable('#tableFormSubmissions')

    fetch(`${routeChartData}?${params.toString()}`)
      .then(response => response.json())
      .then(data => {
        const backgroundColors = data.labels.map(
          label => statuses[label] || '#95a5a6',
        )
        createChart(data, backgroundColors)
      })
  }

  function loadFormSubmissionsTable(partnerId, statusId) {
    const url = `/reports/form_submissions_by_status/${partnerId}/${statusId}`
    loadFormSubmissionsTableBase({
      url,
      startDate,
      endDate,
      statuses,
      rowRenderer: (f, statuses) => {
        let data = JSON.parse(f.data)
        return [
          `<a href="/form_submissions/${f.id}">${data.name}</a>`,
          `<a href="/form_submissions/${f.id}">${data.email}</a>`,
          `<a href="/form_submissions/${f.id}">${data.phone}</a>`,
          `<a href="/form_submissions/${f.id}"><span class="badge" style="color:white;background-color: ${statuses[f.status?.name]};">${f.status?.name || 'N/A'}</span></a>`,
          `<a href="/form_submissions/${f.id}">${new Date(f.created_at).toLocaleDateString()}</a>`,
        ]
      },
    })
  }

  fetchChartData()

  startDate.addEventListener('change', fetchChartData)
  endDate.addEventListener('change', fetchChartData)
  partnerId.addEventListener('change', fetchChartData)
})

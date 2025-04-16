import { resetTable, getStartAndEndDate } from './utils'

export function loadFormSubmissionsTableBase({
  url,
  startDate,
  endDate,
  statuses = null,
  rowRenderer,
}) {
  const { start, end } = getStartAndEndDate(startDate, endDate)
  resetTable('#tableFormSubmissions')

  fetch(`${url}/${start}/${end}`)
    .then(response => response.json())
    .then(formularios => {
      if (formularios.length > 0) {
        let table = $('#tableFormSubmissions').DataTable({
          responsive: true,
          lengthChange: false,
          autoWidth: false,
          destroy: true,
          order: [[4, 'desc']],
          language: { url: '/locales/dataTables_es-ES.json' },
          buttons: ['excel', 'pdf', 'print', 'colvis'],
          initComplete: function () {
            this.api()
              .buttons()
              .container()
              .appendTo('#tableFormSubmissions_wrapper .col-md-6:eq(0)')
          },
        })

        formularios.forEach(f => {
          table.row.add(rowRenderer(f, statuses))
        })

        table.draw()
        $('#tableFormSubmissions').show()
        table
          .buttons()
          .container()
          .appendTo('#tableFormSubmissions_wrapper .col-md-6:eq(0)')
      } else {
        $('#tableFormSubmissions').hide()
      }
    })
}

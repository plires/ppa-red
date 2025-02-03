import $ from 'jquery'
import 'datatables.net'

$('#formSubmissionTable').DataTable({
  order: [[0, 'desc']],
  stateSave: true,
  scrollX: true,
  language: {
    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
  },
})

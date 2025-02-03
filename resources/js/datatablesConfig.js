import $ from 'jquery'
import 'datatables.net'

$('#formSubmissionTable').DataTable({
  order: [[0, 'desc']],
  stateSave: true,
  scrollX: true,
  language: {
    url: '/locales/dataTables_es-ES.json',
  },
})

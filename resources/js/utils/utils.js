/**
 * Limpia y reinicia una tabla de DataTables.
 * @param {string} tableId - Selector del ID de la tabla, ejemplo: "#miTabla"
 */
export function resetTable(tableId) {
  if ($.fn.DataTable.isDataTable(tableId)) {
    $(tableId).DataTable().clear().destroy()
  }
  $(tableId + ' tbody').empty()
}

/**
 * Obtiene fechas de inicio y fin asegurando que estén dentro de los límites válidos.
 * @param {HTMLElement} startDate - input con fecha de inicio
 * @param {HTMLElement} endDate - input con fecha de fin
 * @returns {{ start: string, end: string }}
 */
export function getStartAndEndDate(startDate, endDate) {
  const today = new Date().toISOString().split('T')[0]

  startDate.setAttribute('max', today)
  endDate.setAttribute('max', today)

  let start = startDate.value || '1900-01-01'
  let end = endDate.value || today

  return { start, end }
}

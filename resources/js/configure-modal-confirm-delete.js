document.addEventListener('click', function (event) {
  // Verifica si el clic fue en un botón con la clase 'delete-btn'
  if (event.target.classList.contains('delete-btn')) {
    // Obtén los datos del botón
    const deleteRoute = event.target.getAttribute('data-delete-route')
    var entity = event.target.getAttribute('data-entity')
    var name = event.target.getAttribute('data-name')
    var title = `¿Estás seguro de que deseas eliminar ${entity} ${name}?`

    // Actualiza el formulario de eliminación dentro del modal
    const deleteForm = document.getElementById('deleteForm')
    deleteForm.setAttribute('action', deleteRoute)

    // Actualiza el titulo del modal de confirmacion de borrado
    const label = document.getElementById('modalLabel')
    label.innerText = title
  }
})

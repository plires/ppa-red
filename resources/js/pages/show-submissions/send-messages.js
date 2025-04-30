document.addEventListener('DOMContentLoaded', function () {
  // Seleccionamos el botón que abre el modal
  const submitButton = document.querySelector('[data-target="#confirmModal"]')

  if (submitButton) {
    // Cuando se hace clic en el botón "Enviar"
    submitButton.addEventListener('click', function () {
      // Capturamos el valor del input
      const textValue = inputText.value

      // Mostramos el valor en el modal
      modalText.textContent = textValue
    })
  }

  // Seleccionamos el input de texto
  const inputText = document.getElementById('message')

  // Seleccionamos el elemento donde se mostrará el texto en el modal
  const modalText = document.getElementById('modalText')
})

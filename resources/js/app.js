import $ from 'jquery' // Importa jQuery
window.$ = window.jQuery = $ // Asigna jQuery a la variable global `$` y `jQuery`

import './bootstrap'

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css'

// Theme style
import 'admin-lte/dist/css/adminlte.min.css'

import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'admin-lte/dist/js/adminlte.min.js'

import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

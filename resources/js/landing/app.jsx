import React, { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import ProvincesSelect from './components/ProvincesSelect'

function App() {
  return <ProvincesSelect />
}

createRoot(document.getElementById('landing-root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)

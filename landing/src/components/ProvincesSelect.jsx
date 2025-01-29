import React, { useState, useEffect } from 'react'
import axios from 'axios'

const ProvincesSelect = () => {
  const [provinces, setProvinces] = useState([])
  const [zones, setZones] = useState([])
  const [localities, setLocalities] = useState([])

  const [selectedProvince, setSelectedProvince] = useState(null)
  const [selectedZone, setSelectedZone] = useState(null)

  useEffect(() => {
    // Cargar provincias al montar el componente
    axios
      .get('http://127.0.0.1:8000/api/provinces')
      .then(response => setProvinces(response.data))
      .catch(error => console.error('Error cargando provincias:', error))
  }, [])

  const handleProvinceChange = provinceId => {
    setSelectedProvince(provinceId)
    setSelectedZone(null)
    setLocalities([])

    // Cargar zonas de la provincia seleccionada
    axios
      .get(`http://127.0.0.1:8000/api/zones?province_id=${provinceId}`)
      .then(response => {
        setZones(response.data)

        if (response.data.length === 0) {
          // Si la provincia no tiene zonas, cargar directamente sus localidades
          axios
            .get(
              `http://127.0.0.1:8000/api/localities?province_id=${provinceId}`,
            )
            .then(res => setLocalities(res.data))
            .catch(err => console.error('Error cargando localidades:', err))
        } else {
          setLocalities([])
        }
      })
      .catch(error => console.error('Error cargando zonas:', error))
  }

  const handleZoneChange = zoneId => {
    setSelectedZone(zoneId)

    // Cargar localidades de la zona seleccionada
    axios
      .get(`http://127.0.0.1:8000/api/localities?zone_id=${zoneId}`)
      .then(response => setLocalities(response.data))
      .catch(error => console.error('Error cargando localidades:', error))
  }

  return (
    <div>
      {/* Select de Provincias */}
      <label>Provincia:</label>
      <select onChange={e => handleProvinceChange(e.target.value)}>
        <option value=''>Seleccione una provincia</option>
        {provinces.map(province => (
          <option key={province.id} value={province.id}>
            {province.name}
          </option>
        ))}
      </select>

      {/* Select de Zonas (solo si hay zonas en la provincia) */}
      {zones.length > 0 && (
        <>
          <label>Zona:</label>
          <select onChange={e => handleZoneChange(e.target.value)}>
            <option value=''>Seleccione una zona</option>
            {zones.map(zone => (
              <option key={zone.id} value={zone.id}>
                {zone.name}
              </option>
            ))}
          </select>
        </>
      )}

      {/* Select de Localidades */}
      {localities.length > 0 && (
        <>
          <label>Localidad:</label>
          <select>
            <option value=''>Seleccione una localidad</option>
            {localities.map(locality => (
              <option key={locality.id} value={locality.id}>
                {locality.name}
              </option>
            ))}
          </select>
        </>
      )}
    </div>
  )
}

export default ProvincesSelect

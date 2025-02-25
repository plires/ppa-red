<x-app-layout>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('parts.msg-success')
        @include('parts.msg-errors')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Edición de la Localidad</h1>
                    </div>
                </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Editá el nombre de la localidad, la zona y/o provincia a la que
                                    pertenece. También podés asignar un partner.
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->

                            <form class="form-horizontal" method="POST"
                                action="{{ route('localities.update', $locality->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    {{-- Nombre --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input required class="form-control" id="name" placeholder="Nombre"
                                                type="text" name="name"
                                                value="{{ old('name', $locality->name) }}">
                                        </div>
                                    </div>
                                    {{-- Nombre end --}}

                                    {{-- Provincia --}}
                                    <div class="form-group row">
                                        <label for="province_id" class="col-sm-2 col-form-label">Provincia</label>
                                        <div class="col-sm-10">
                                            <select id="province_id" name="province_id" class="form-control" required>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        {{ old('province_id', $locality->province_id) == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Provincia end --}}

                                    {{-- Zona --}}
                                    <div class="mb-3" id="zone-container" style="display: none;">
                                        <div class="form-group row">
                                            <label for="zone_id" class="col-sm-2 col-form-label">Zona</label>
                                            <div class="col-sm-10">
                                                <select id="zone_id" name="zone_id" class="form-control">
                                                    <option value="">Seleccione una zona (obligatorio)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Zona end --}}

                                    {{-- Partner --}}
                                    <div class="form-group row">
                                        <label for="user_id" class="col-sm-2 col-form-label">Partner
                                            Asignado</label>
                                        <div class="col-sm-10">
                                            <select id="user_id" name="user_id" class="form-control" required>
                                                @foreach ($partners as $partner)
                                                    <option value="{{ $partner->id }}"
                                                        {{ old('user_id', $locality->user_id) == $partner->id ? 'selected' : '' }}>
                                                        {{ $partner->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Partner end --}}

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info float-right ml-3">Guardar
                                        cambios</button>
                                    <a class="btn btn-default float-right" href="{{ route('localities.index') }}">
                                        Cancelar y volver
                                    </a>
                                </div>
                                <!-- /.card-footer -->
                            </form>

                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let provinceSelect = document.getElementById('province_id');
                let zoneSelect = document.getElementById('zone_id');
                let zoneContainer = document.getElementById('zone-container');

                function loadZones(provinceId, selectedZoneId = null) {
                    if (!provinceId) {
                        zoneSelect.innerHTML = '<option value="">Seleccione una provincia primero</option>';
                        zoneContainer.style.display = 'none';
                        return;
                    }

                    fetch(`/api/get-zones/${provinceId}`)
                        .then(response => response.json())
                        .then(zones => {
                            zoneSelect.innerHTML = '';
                            if (zones.length > 0) {
                                zoneContainer.style.display = 'block';
                                zoneSelect.innerHTML =
                                    '<option value="">Seleccione una zona (obligatorio)</option>';
                                zones.forEach(zone => {
                                    let option = document.createElement('option');
                                    option.value = zone.id;
                                    option.textContent = zone.name;
                                    if (selectedZoneId && zone.id == selectedZoneId) {
                                        option.selected = true;
                                    }
                                    zoneSelect.appendChild(option);
                                });
                            } else {
                                zoneContainer.style.display = 'none';
                                zoneSelect.innerHTML =
                                    '<option value="">No existen zonas para la provincia seleccionada</option>';
                            }
                        })
                        .catch(error => {

                            console.error('error al obtener provincoas y zonas')
                            // TODO: ver si podemos hacer que aca muestre el error por pantalla.

                        });
                }

                // Cargar zonas cuando se cambia la provincia
                provinceSelect.addEventListener('change', function() {
                    loadZones(this.value);
                });

                // Cargar las zonas si hay una provincia seleccionada (para edición)
                let initialProvinceId = provinceSelect.value;
                let initialZoneId = "{{ old('zone_id', $locality->zone_id) }}";
                if (initialProvinceId) {
                    loadZones(initialProvinceId, initialZoneId);
                }
            });
        </script>
    @endsection
</x-app-layout>

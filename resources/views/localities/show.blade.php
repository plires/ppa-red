<x-app-layout>

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Detalle de la localidad {{ $locality->name }}</h1>
                    </div>
                </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        {{-- Datos --}}
                        <div class="card card-secondary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span class="ml-2">Datos de la localidad {{ $locality->name }}</span>
                                </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="display: block;">
                                <dl>
                                    <dt>
                                        Nombre
                                    </dt>
                                    <dd>
                                        {{ $locality->name }}
                                    </dd>

                                    <dt>
                                        Asignado a partner
                                    </dt>
                                    <dd>
                                        {{ $locality->user->name }}
                                    </dd>

                                    <dt>
                                        Pertenece a la provincia
                                    </dt>
                                    <dd>
                                        {{ $locality->province->name }}
                                    </dd>

                                    @if ($locality->zone)
                                        <dt>
                                            Pertenece a la zona
                                        </dt>
                                        <dd>
                                            {{ $locality->zone->name }}
                                        </dd>
                                    @endif

                                </dl>
                            </div>
                            <div class="card-footer">
                                <div class="content-buttons">
                                    <a href="{{ route('localities.edit', $locality->id) }}" type="button"
                                        class="btn btn-primary btn-sm float-right">
                                        Editar datos Localidad
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- Datos end --}}

                    </div>
                </div>
            </div>
        </section>
    </div>

</x-app-layout>

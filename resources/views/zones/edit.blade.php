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
                        <h1>Edición de la Zona</h1>
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
                                <h3 class="card-title">Editá el nombre de la zona y la provincia a la que pertenece.
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" method="POST"
                                action="{{ route('zones.update', $zone->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="name" placeholder="Nombre"
                                                type="text" name="name" value="{{ old('name', $zone->name) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Provincia</label>
                                        <div class="col-sm-10">
                                            <select name="province_id" class="form-control">

                                                @if ($provinces->isEmpty())
                                                    <option>No existen provincias para enlazar esta zona</option>
                                                @else
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id === $zone->province_id ? 'selected' : '' }}>
                                                            {{ $province->name }}
                                                        </option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info float-right ml-3">Guardar
                                        cambios</button>
                                    <a class="btn btn-default float-right" href="{{ route('zones.index') }}">
                                        Cancelar y volver
                                    </a>
                                </div>
                                <!-- /.card-footer -->
                            </form>
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
</x-app-layout>

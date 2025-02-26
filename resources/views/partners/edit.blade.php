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
                        <h1>Edición del Partner</h1>
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
                                <h3 class="card-title">Editá los datos del partner.
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" method="POST"
                                action="{{ route('partners.update', $partner->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="name" placeholder="Nombre"
                                                type="text" name="name" value="{{ old('name', $partner->name) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="name" placeholder="Email"
                                                type="email" name="email"
                                                value="{{ old('email', $partner->email) }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Teléfono</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="name" placeholder="Teléfono"
                                                type="text" name="phone"
                                                value="{{ old('phone', $partner->phone) }}">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info float-right ml-3">Guardar
                                        cambios</button>
                                    <a class="btn btn-default float-right" href="{{ route('partners.index') }}">
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

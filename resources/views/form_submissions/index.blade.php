@extends('adminlte::page')

@section('title', 'Formularios')

@section('content_header')
    <h1>Listado de Formularios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('form_submissions.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre"
                        value="{{ request('search') }}">
                    <select name="sort_by" class="form-control">
                        <option value="">Ordenar por</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nombre</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Fecha de
                            Creación</option>
                    </select>
                    <select name="order" class="form-control">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descendente</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formSubmissions as $form)
                        <tr>
                            <td>{{ $form->id }}</td>
                            <td>{{ $form->name }}</td>
                            <td>{{ $form->email }}</td>
                            <td>{{ $form->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('form_submissions.show', $form->id) }}"
                                    class="btn btn-info btn-sm">Ver</a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('form_submissions.edit', $form->id) }}"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('form_submissions.destroy', $form->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Seguro que deseas eliminar?')">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $formSubmissions->links() }}
        </div>
    </div>
@stop

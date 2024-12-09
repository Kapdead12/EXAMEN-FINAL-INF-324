<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistema de Gestion de Freelancers</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
		
		<div class="container my-4">
			<h4>TABLA DE ESPECIALIDADES</h4>
			<table class="table table-dark table-striped table-hover table-sm">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
					</tr>
				</thead>
				<tbody>
					@foreach($especialidades as $especialidad)
					<tr>
						<td>{{ $especialidad->id }}</td>
						<td>{{ $especialidad->nombre }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<h4>TABLA DE FREELANCERS</h4>
			<table class="table table-dark table-striped table-hover table-sm">
				<thead>
					<tr>
						<th>ID</th>
						<th>CI</th>
						<th>Nombre</th>
						<th>Especialidad</th>
						<th>Contacto</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody>
				@if(session('success'))
					<div class="alert alert-success">
						{{ session('success') }}
					</div>
				@endif

				@if(session('error'))
					<div class="alert alert-danger">
						{{ session('error') }}
					</div>
				@endif
					@foreach($freelancers as $freelancer)
					<tr>
						<td>{{ $freelancer->id }}</td>
						<td>{{ $freelancer->ci }}</td>
						<td>{{ $freelancer->nombre }}</td>
						<td>{{ $freelancer->especialidad }}</td>
						<td>{{ $freelancer->contacto }}</td>
						<td>
							<a href="{{ route('editar', $freelancer->ci) }}" class="btn btn-sm btn-warning">Editar</a>
							<!-- Eliminar Button -->
							<form action="{{ route('eliminar', $freelancer->ci) }}" method="POST" style="display:inline;">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta persona?')">
									Eliminar
								</button>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<a href="{{ route('adicionar') }}" class="btn btn-md btn-success">Adicionar</a>
		</div>
    </body>
</html>

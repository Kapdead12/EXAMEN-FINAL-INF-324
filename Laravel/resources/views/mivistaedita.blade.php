<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Editar Freelancer</title>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
		<div class="container px-1">
			<h3 class="mt-4">Edición de Freelancer</h3>
			@if(session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
				</div>
			@endif
			@if(session('success'))
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			@endif
			<form action="{{ route('modificar') }}" method="post" class="mt-3">
				@csrf
				<div class="mb-3">
					<label for="id" class="form-label">ID</label>
					<input type="text" id="id" name="id" class="form-control" value="{{ $freelancer->id }}" readonly>
				</div>
				<div class="mb-3">
					<label for="ci" class="form-label">CI</label>
					<input type="text" id="ci" name="ci" class="form-control" value="{{ $freelancer->ci }}" required>
				</div>
				<div class="mb-3">
					<label for="nombre" class="form-label">Nombre</label>
					<input type="text" id="nombre" name="nombre" class="form-control" value="{{ $freelancer->nombre }}" required>
				</div>
				<div class="mb-3">
					<label for="especialidad_id" class="form-label">Especialidad</label>
					<select id="especialidad_id" name="especialidad_id" class="form-select" required>
						@foreach($especialidades as $especialidad)
							<option value="{{ $especialidad->id }}" 
								{{ $freelancer->especialidad_id == $especialidad->id ? 'selected' : '' }}>
								{{ $especialidad->nombre }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label for="contacto" class="form-label">Contacto</label>
					<input type="text" id="contacto" name="contacto" class="form-control" value="{{ $freelancer->contacto }}" required>
				</div>
				<button type="submit" class="btn btn-primary">Modificar</button>
			</form>
		</div>
    </body>
</html>

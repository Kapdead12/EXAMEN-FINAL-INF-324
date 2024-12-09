<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class miControlador extends Controller
{
    // index
	public function index($minombre)
	{
		return "Hola ".$minombre;
	}
	
	public function lista()
	{
		$datosFreelancers = DB::select("
			SELECT 
				freelancers.id,
				freelancers.ci,
				freelancers.nombre,
				especialidades.nombre AS especialidad,
				freelancers.contacto
			FROM freelancers
			JOIN especialidades ON freelancers.especialidad_id = especialidades.id
		");

		$datosEspecialidades = DB::select("
			SELECT 
				id, 
				nombre 
			FROM especialidades
		");

		return view('mivistadatos')->with([
			"freelancers" => $datosFreelancers,
			"especialidades" => $datosEspecialidades
		]);
	}
	
	public function edita($ci)
	{
		$datos = DB::select("select * from freelancers where ci = ?", [$ci]);
		
		$especialidades = DB::select("select * from especialidades");
		
		return view('mivistaedita', [
			'freelancer' => $datos[0], 
			'especialidades' => $especialidades
		]);
	}

	public function modificar(Request $request)
	{
		$request->validate([
			'ci' => 'required|unique:freelancers,ci,' . $request->input('id'), 
			'nombre' => 'required|string|max:255',
			'especialidad_id' => 'required|exists:especialidades,id',
			'contacto' => 'required|string|max:255',
		]);

		$datos = DB::update(
			"UPDATE freelancers 
			SET ci = ?, nombre = ?, especialidad_id = ?, contacto = ? 
			WHERE id = ?",
			[
				$request->input('ci'),  
				$request->input('nombre'),
				$request->input('especialidad_id'),
				$request->input('contacto'),
				$request->input('id'),
			]
		);

		if ($datos) {
			return redirect()->route('listado')
							->with('success', 'Freelancer actualizado con éxito.');
		} else {
			return redirect()->back()->with('error', 'No se encontró la persona o ocurrió un error al modificar');
		}
	}


	public function eliminar($ci)
	{
		$resultado = DB::delete("DELETE FROM freelancers WHERE ci = ?", [$ci]);

		if ($resultado) {
			return redirect()->route('listado')->with('success', 'Freelancer con CI {$ci} eliminado con éxito.');
		} else {
			return redirect()->back()->with('error', 'No se encontró al freelancer o ocurrió un error al eliminar.');
		}
	}

	public function adicionar()
	{
		$especialidades = DB::select("select * from especialidades");
		return view('mivistaAdicionar', [
			'especialidades' => $especialidades
		]);
	}

	public function agregar(Request $request)
	{
		$request->validate([
			'ci' => 'required|unique:freelancers,ci', 
			'nombre' => 'required|string|max:255',   
			'especialidad_id' => 'required|exists:especialidades,id', 
			'contacto' => 'required|string|max:255',  
		]);

		$resultado = DB::insert("INSERT INTO freelancers (ci, nombre, especialidad_id, contacto) VALUES (?, ?, ?, ?)", [
			$request->ci,  
			$request->nombre,  
			$request->especialidad_id, 
			$request->contacto
		]);

		if ($resultado) {
			return redirect()->route('listado')->with('success', 'Freelancer agregado con éxito');
		} else {
			return back()->with('error', 'Error al agregar el freelancer');
		}
	}


}

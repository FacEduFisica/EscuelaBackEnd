<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matricula;
use Illuminate\Support\Facades\Validator;

class MatriculaController extends Controller
{
    public function store(Request $request) 
    {
		$validateMatricula =  Validator::make($request->all(), [
			'id_persona' => 'required|string',
			'id_curso' => 'required|string',
		]);
		if($validateMatricula->fails()) {
            return response()
                ->json(['status' => '500', 'data' => $validateCourse->errors()]);
        }
		$matricula = Matricula::create($validateMatricula->getData());
		
		return response()
		->json(['status' => '200', 'data' => 'Matricula Creada']);
	}
}

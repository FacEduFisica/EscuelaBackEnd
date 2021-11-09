<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Programa;
use App\Models\Curso;

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Programa::all();
        return $programs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateProgram = Validator::make($request->all(), [
            'nombre' => 'required | string',
            'descripcion' => 'required | string',
            'valor' => 'required |integer|gt:0',
            //'estado' => 'required|in:Activo,Inactivo' 
        ]);

        if($validateProgram->fails()) {
            return response()
                    ->json(['status' => '500', 'data' => $validateProgram->errors()]);
        }
        
        $program = Programa::create($validateProgram->getData());
        return response()
                    ->json(['status' => '200', 'data' => "Programa Creado"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Programa::where('id',$id)->first();
        return response()
                    ->json(['status' => '200', 'data' => $program]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function detailPrograma($id) 
    {
        $programCourses = Programa::join('cursos','programas.id','=','cursos.id_programa')
            ->select('programas.nombre','programas.descripcion','programas.valor',
            'programas.estado','cursos.nombre as nombre_curso','cursos.descripcion as descripcion_curso',
            'cursos.edad_min','cursos.edad_max','cursos.cupos',Curso::raw('cursos.cupos - cursos.cupos_ocupados as cupos_disponibles'),
            'cursos.hora_inicial','cursos.hora_final','cursos.estado as estado_curso')
            ->where('cursos.id_programa',$id)->get();
            return response()
            ->json(['status' => '200', 'data'=>$programCourses]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $program = Programa::where('id',$id)->update([
            "nombre" => $request->nombre,
            "descripcion" => $request->descripcion,
            "valor" => $request->valor,
            "estado" => $request->estado
        ]);

        return response()
            ->json(['status' => '200']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $program = Programa::where('id','=',$id);
        $program->delete();
        return response()
            ->json(['status' => '200']);
    }
}

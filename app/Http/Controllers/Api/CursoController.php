<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Curso;
use App\Models\Programa;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function courses() 
    {
        $courses = Curso::join('programas','programas.id','=','cursos.id_programa')
            ->join('users','cursos.id_profesor','=','users.id')
            ->select('programas.nombre as programa','programas.descripcion','programas.valor',
            'programas.estado','cursos.nombre as nombre_curso','cursos.descripcion as descripcion_curso',
            'cursos.edad_min','cursos.edad_max','cursos.cupos',Curso::raw('cursos.cupos - cursos.cupos_ocupados as cupos_disponibles'),
            'cursos.hora_inicial','cursos.hora_final','cursos.estado as estado_curso','cursos.id',
            'cursos.id_programa','cursos.id_profesor',User::raw('CONCAT(users.nombre," ",users.apellido) AS nombre_profesor'))
            ->get();

            return response()
            ->json(['status' => '200', 'data'=>$courses]);
    }

    public function listTeachers() 
    {
        $teachers = User::where('user_type','Profesor')
        ->where('is_active',1)
        ->select('users.id',User::raw('CONCAT(users.nombre," ",users.apellido) AS nombre_completo'))
        ->get();
        return response()
            ->json(['status' => '200','data'=>$teachers]);
    }

    public function listPrograms()
    {
        $programs = Programa::where('estado','Activo')
        ->select('programas.id','programas.nombre')   
        ->get();
        return response()
            ->json(['status' => '200','data'=>$programs]);
    }

    public function listCoursesAvailable()
    {
        $courses = Curso::where('estado','Abierto')
        ->whereRaw('cupos_ocupados < cupos')
        ->get();

        return response()
            ->json(['status' => '200', 'data'=>$courses]);
    }

    public function closeCourse($id, Request $request) {
        $course = Curso::where('id',$id)->update([
            "estado" => $request->estado
        ]);

        return response()
            ->json(['status' => '200']);
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
        $validateCourse = Validator::make($request->all(),[
            'id_programa' => 'required|numeric|gt:0',
            'id_profesor'=> 'required|numeric|gt:0',
            'nombre'=>'required|regex:/^[\pL\s\-]+$/u',
            'descripcion'=>'required|string',
            'cupos'=> 'required|numeric|gt:0',
            'edad_min'=> 'required|numeric|gt:0',
            'edad_max'=> 'required|numeric|gt:0',
            'hora_inicial' =>'required',
            'hora_final' => 'required',
            //'estado' => 'required|in:Abierto,Cerrado'
        ]);

        if($validateCourse->fails()) {
            return response()
                ->json(['status' => '500', 'data' => $validateCourse->errors()]);
        }

        $course = Curso::create($validateCourse->getData());
        return response()
                    ->json(['status' => '200', 'data' => 'Curso Creado']);

    }

    public function updateCourse(Request $request,$id)
    {
        $course = Curso::where('id',$id)->update([
            'id_programa' => $request->id_programa,
            'id_profesor'=> $request->id_profesor,
            'nombre'=>$request->nombre,
            'descripcion' => $request->descripcion,
            'cupos'=> $request->cupos,
            'edad_min'=> $request->edad_min,
            'edad_max'=> $request->edad_max,
            'hora_inicial' => $request->hora_inicial,
            'hora_final' => $request->hora_final,
            'estado' => $request->estado,
        ]);

        return response()
            ->json(['status' => '200', 'data' => 'Curso Actualizado']);
    }

    public function destroyCourse($id)
    {
        $course = Curso::where('id','=',$id);
        $course->delete();
        return response()
            ->json(['status' => '200', 'data' => 'Curso Eliminado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

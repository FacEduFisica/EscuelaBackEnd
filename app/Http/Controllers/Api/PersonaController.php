<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        //
    }

    public function storeTeacher(Request $request) 
    {
        /*
        Validar si el profesor es mayor de edad
        $fecha_nacimiento = explode("-",$request->fecha_nacimiento);
        $fecha_nacimiento_carbon = Carbon::create($fecha_nacimiento[0],$fecha_nacimiento[1],$fecha_nacimiento[2]);
        $fecha_carbon = Carbon::now()->subYears(18);

        
        if($fecha_nacimiento_carbon->greaterThan($fecha_carbon)) {
            return response()
                ->json(['status' => '500', 'data' => 'Validar Fecha, Profesor menor de edad']);
        }*/

        $validateTeacher = Validator::make($request->all(), [
            'tipo_documento' => 'required|in:CC,TI,CE,RC,Otro',
            'numero_documento' => 'required|numeric|unique:personas',
            'departamento_expedicion' => 'required|string',
            'municipio_expedicion' => 'required|string',
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u',
            'fecha_nacimiento' => 'required',
            'lugar_nacimiento' => 'required|string',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'direccion' => 'required|string',
            'telefono' => 'required|numeric',
            'email' => 'required|email',
            'eps' => 'required|string',
            'nombre_contacto_emergencia' => 'required|regex:/^[\pL\s\-]+$/u',
            'numero_contacto_emergencia' => 'required|numeric',
            'id_empresa'  => 'required|string',
            'tipo_vinculacion' => 'required|in:Empleado,Egresado,Estudiante',
        ]);

        if($validateTeacher->fails()) {
            return response()
                ->json(['status' => '500', 'data' => $validateTeacher->errors()]);
        }

        //$fecha_nacimiento = explode("-",$request->fecha_nacimiento);
        //$fecha_nacimiento_carbon = Carbon::create($fecha_nacimiento[0],$fecha_nacimiento[1],$fecha_nacimiento[2]);

        $user = auth()->user();
        $teacher = new Persona();
        $teacher->id_usuario = $user->id;
        $teacher->user_type = 'Profesor';
        $teacher->tipo_documento = $request->tipo_documento;
        $teacher->numero_documento = $request->numero_documento;
        $teacher->departamento_expedicion = $request->departamento_expedicion;
        $teacher->municipio_expedicion = $request->municipio_expedicion;
        $teacher->nombre = $request->nombre;
        $teacher->apellido = $request->apellido;
        $teacher->fecha_nacimiento = $request->fecha_nacimiento['year'].'-'.$request->fecha_nacimiento['month'].'-'.$request->fecha_nacimiento['day'];
        $teacher->lugar_nacimiento = $request->lugar_nacimiento;
        $teacher->genero = $request->genero;
        $teacher->direccion = $request->direccion;
        $teacher->telefono = $request->telefono;
        $teacher->email = $request->email;
        $teacher->eps = $request->eps;
        $teacher->nombre_contacto_emergencia = $request->nombre_contacto_emergencia;
        $teacher->numero_contacto_emergencia = $request->numero_contacto_emergencia;
        $teacher->id_empresa = $request->id_empresa;
        $teacher->tipo_vinculacion = $request->tipo_vinculacion;
        $teacher->save();

        return response()
                    ->json(['status' => '200', 'data' => "Profesor Creado"]);
    }

    public function listTeachers() 
    {
        $teachers = Persona::where('personas.user_type','=','Profesor')
        ->join('empresas','personas.id_empresa','=','empresas.id')
        ->select('personas.*','empresas.nombre as nombre_empresa')
        ->get();
        return $teachers;
    }

    public function destroyTeacher($id) 
    {
        $teacher = Persona::where('id','=',$id)->first();
        $email = $teacher->email;
        $user = User::where('email','=',$email)->first();
        $teacher->delete();
        $user->delete();
        return response()
            ->json(['status' => '200','data'=>'Profesor Eliminado']);
    }

    public function updateTeacher(Request $request,$id) 
    {
        $teacher = Persona::where('id',$id)->update([
            'tipo_documento' => $request->tipo_documento,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'lugar_nacimiento' => $request->lugar_nacimiento,
            'genero' => $request->genero,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'eps' => $request->eps,
            'nombre_contacto_emergencia' => $request->nombre_contacto_emergencia,
            'numero_contacto_emergencia' => $request->numero_contacto_emergencia,
            'empresa'  => $request->empresa,
            'tipo_vinculacion' => $request->tipo_vinculacion
        ]);

        $teacher = Persona::where('id',$id)->first();
        $email = $teacher->email;
        $user = User::where('email','=',$email)->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido
        ]);
        return response()
            ->json(['status' => '200']);
    }


    public function storeKidStudent(Request $request)
    {
        $validatekidStudent = Validator::make($request->all(), [
            'tipo_documento' => 'required|in:CC,TI,CE,RC,Otro',
            'numero_documento' => 'required|numeric|unique:personas',
            'departamento_expedicion' => 'required|string',
            'municipio_expedicion' => 'required|string',
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u',
            'fecha_nacimiento' => 'required',
            'lugar_nacimiento' => 'required|string',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'direccion' => 'required|string',
            'telefono' => 'required|numeric',
            'eps' => 'required|string',
            //'nombre_contacto_emergencia' => 'required|regex:/^[\pL\s\-]+$/u',
            'numero_contacto_emergencia' => 'required|numeric',
            'id_empresa'  => 'required',
            'tipo_vinculacion' => 'required|in:Empleado,Egresado,Estudiante',
            'nombre_establecimiento' => 'required',
            'tipo_establecimiento' => 'required|in:Privado,Oficial',
            'foto' => 'required',
            'nombre_padre' => 'regex:/^[\pL\s\-]+$/u',
            'celular_padre' => 'numeric',
            'nombre_madre' => 'regex:/^[\pL\s\-]+$/u',
            'celular_madre' => 'numeric',
            'estudia' => 'string',
            'grado_escolar' => 'in:Primaria incompleta,Primaria completa,Secundaria incompleta,Secundaria completa,Universitario,Otro',
        ]);
		$user = auth()->user();
		$kidStudent = new Persona();
        $kidStudent->id_usuario = $user->id;
        $kidStudent->user_type = 'Niño';
        $kidStudent->tipo_documento = $request->tipo_documento;
        $kidStudent->numero_documento = $request->numero_documento;
        $kidStudent->departamento_expedicion = $request->departamento_expedicion;
        $kidStudent->municipio_expedicion = $request->municipio_expedicion;
        $kidStudent->nombre = $request->nombre;
        $kidStudent->apellido = $request->apellido;
        $kidStudent->fecha_nacimiento = $request->fecha_nacimiento['year'].'-'.$request->fecha_nacimiento['month'].'-'.$request->fecha_nacimiento['day'];
        $kidStudent->lugar_nacimiento = $request->lugar_nacimiento;
        $kidStudent->genero = $request->genero;
        $kidStudent->direccion = $request->direccion;
        $kidStudent->telefono = $request->telefono;
      //  $person->email = $request->email;
        $kidStudent->eps = $request->eps;
        //$person->nombre_contacto_emergencia = $request->nombre_contacto_emergencia;
        $kidStudent->numero_contacto_emergencia = $request->numero_contacto_emergencia;
        $kidStudent->id_empresa = $request->id_empresa;
        $kidStudent->tipo_vinculacion = $request->tipo_vinculacion;
		$kidStudent->foto = $request->foto;
        $kidStudent->save();

       /* if($kidStudent->fails()) {
            return response()
                ->json(['status' => '500', 'data' => $kidStudent->errors()]);
        }*/
        

        //$user = auth()->user();

        return response()
                ->json(['status' => '500', 'data' => $kidStudent]);
        if($user->user_type = 'Admin') {
            return response()
                ->json(['status' => '500', 'data' => 'Soy Admin', $request->all()]);
        }else {
            return response()
                ->json(['status' => '500', 'data' => 'No soy Admin', $request->all()]);
        }


    }

	public function listStudents() 
    {
        $kidStudent = Persona::where('personas.user_type','!=','Profesor')
        ->join('empresas','personas.id_empresa','=','empresas.id')
        ->select('personas.*','empresas.nombre as nombre_empresa')
        ->get();
        return $kidStudent;
    }

    public function listGuardians()
    {
        $guardians = User::where('user_type','Acudiente')
        ->where('is_active',1)->get();
        return $guardians;
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

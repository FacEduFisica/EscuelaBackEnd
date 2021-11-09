<?php

namespace App\Http\Controllers\Api;
use App\Models\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Empresa::all();
        return $companies;
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
        $validateCompany = Validator::make($request->all(),[
            'nombre' => 'required|string'
        ]);

        if($validateCompany->fails()) {
            return response()
                    ->json(['status' => '500', 'data' => $validateCompany->errors()]);
        }
        
        $company = Empresa::create($validateCompany->getData());
        return response()
                    ->json(['status' => '200', 'data' => "Empresa Creada"]);
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

        $validateCompany = Validator::make($request->all(),[
            'nombre' => 'required|string'
        ]);

        if($validateCompany->fails()) {
            return response()
                    ->json(['status' => '500', 'data' => $validateCompany->errors()]);
        }

        $company = Empresa::where('id',$id)->update([
            'nombre' => $request->nombre
        ]);

        if($company) {
            return response()
                    ->json(['status' => '200', 'data' => "Empresa Actualizada"]);
        }else {
            return response()
                    ->json(['status' => '404', 'data' => "No existe la Empresa"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Empresa::where('id','=',$id)->first();
        if($company) {
            $company->delete();
            return response()
                    ->json(['status' => '200', 'data' => "Empresa Eliminada"]);
        }else {
            return response()
                    ->json(['status' => '404', 'data' => "No existe la Empresa"]);
        }
    }
}

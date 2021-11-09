<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Noticia;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = Noticia::select('id','titulo','subtitulo','tema','imagen','contenido')
        ->orderByRaw('created_at desc')
        ->get();
        return $news;
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
        $validateNew = Validator::make($request->all(),[
            'titulo' => 'required|string',
            'subtitulo' => 'required|string',
            'tema' => 'required|string',
            'imagen' => 'required|string',
            'contenido' => 'required|string',
        ]);

        if($validateNew->fails()) {
            return response()
                ->json(['status' => '500', 'data' => $validateNew->errors()]);
        }

        $new = Noticia::create($validateNew->getData());
        return response()
                    ->json(['status' => '200', 'data' => "Noticia Creada"]);
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
        $new = Noticia::where('id',$id)->update([
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'tema' => $request->tema,
            'contenido' => $request->contenido,
        ]);

        return response()
            ->json(['status' => '200', 'data'=>'Noticia Actualizada']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $new = Noticia::where('id',$id);
        $new->delete();
        return response()
            ->json(['status' => '200']);
    }
}

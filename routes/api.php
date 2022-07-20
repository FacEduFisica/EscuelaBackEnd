<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ProgramaController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\NoticiaController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\MatriculaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('cors, auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/usuario/register',[AuthController::class,'register']);
Route::post('/usuario/login',[AuthController::class,'login'])->middleware('exist','actived','verified');

Route::post('/password/email',[ForgotPasswordController::class,'sendResetLinkEmail']);
Route::post('/password/reset',[ResetPasswordController::class,'reset']);
Route::get('/email/resend',[VerificationController::class,'resend'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify');

Route::group(['middleware'=>['actived.system','verified','auth:api']],function() {
    //Administrador
    Route::group(['middleware'=>['role']],function() {
        //Registrar usuario
        Route::post('/admin/usuario/register',[AuthController::class,'adminRegister']);
        //Obtener usuarios
        Route::get('/usuario',[AuthController::class,'users']);
        //Borrar Usuario
        Route::delete('/usuario/{id}',[AuthController::class,'destroy']);
        //Actualizar Usuario
        Route::put('/usuario',[AuthController::class,'update']);

        //Programas
        //Crear Programa
        Route::post('/programa',[ProgramaController::class,'store']);
        //Borrar Programa
        Route::delete('/programa/{id}',[ProgramaController::class,'destroy']);
        //Actualizar Programa
        Route::put('/programa/{id}',[ProgramaController::class,'update']);

        //Profesores
        //Obtener Profesores
        Route::get('/profesores',[PersonaController::class,'listTeachers']);
        //Crear Profesor
        Route::post('/profesor',[PersonaController::class,'storeTeacher']);
        //Eliminar Profesor
        Route::delete('/profesor/{id}',[PersonaController::class,'destroyTeacher']);
        //Actualizar Profesor
        Route::put('/profesor/{id}',[PersonaController::class,'updateTeacher']);



        //Cursos
        //Obtener Cursos
        Route::get('/cursos',[CursoController::class,'courses']);
        //Crear Curso
        Route::post('/curso',[CursoController::class,'store']);
        //Actualizar Curso
        Route::put('/cursos/{id}',[CursoController::class,'updateCourse']);
        //Eliminar Curso
        Route::delete('/curso/{id}',[CursoController::class,'destroyCourse']);
        //Cerrar Curso
        Route::put('/curso/{id}',[CursoController::class,'closeCourse']);

        //Noticias
        //Crear Noticia
        Route::post('/noticias',[NoticiaController::class,'store']);
        //Actualizar Noticia
        Route::put('/noticias/{id}',[NoticiaController::class,'update']);
        //Eliminar Noticia
        Route::delete('/noticias/{id}',[NoticiaController::class,'destroy']);

        //Listar Acudientes
        Route::get('/acudientes',[PersonaController::class,'listGuardians']);

		Route::post('/matricula',[MatriculaController::class,'store']);


        //Estudiantes
        //Registrar Niño
        Route::post('/estudiante/kid',[PersonaController::class,'storeKidStudent']);
		//Obtener personas inscirtas
		Route::get('/estudiantes',[PersonaController::class,'listStudents']);
        //Empresas
        //Crear Empresa
        Route::post('/empresas',[EmpresaController::class,'store']);
        //Actualizar Empresa
        Route::put('/empresas/{id}',[EmpresaController::class,'update']);
        //Eliminar Empresa
        Route::delete('/empresas/{id}',[EmpresaController::class,'destroy']);
    });

    Route::get('/empresas',[EmpresaController::class,'index']);
    Route::get('/cursos/profesores',[CursoController::class,'listTeachers']);
    Route::get('/cursos/programas',[CursoController::class,'listPrograms']);
    Route::get('/cursos/disponibles',[CursoController::class,'listCoursesAvailable']);
    Route::get('/logout',[AuthController::class,'logout']);
});

//Obtener Noticias
Route::get('/noticias',[NoticiaController::class,'index']);
//Obtener Programas
Route::get('/programas',[ProgramaController::class,'index']);

//Obtener Detalle Programa
Route::get('/programas/getdetalle/{id}',[ProgramaController::class,'detailPrograma']);
Route::get('/programas/getprograma/{id}',[ProgramaController::class,'show']);
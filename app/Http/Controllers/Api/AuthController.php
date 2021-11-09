<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageStatus;

class AuthController extends Controller
{
    public function register(Request $request) {
        $usuario =  User::where('email','=', $request->email)->first();
        if($usuario)
        {
            return response()
            ->json(['status' => '500', 'data' => "El usuario ya existe"]);
        }

        $validateData = $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u',
            'email'=>'email|required|unique:users',
            'password'=>'required|alpha_num|confirmed'
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        if($request->rol){
            $user_type = ucwords(strtolower($request->rol));
            $user->user_type = $user_type;
        }

        $user->save();
        event(new Registered($user));

        $new_user =  User::where('email','=', $request->email)->first();

        return response()
                    ->json(['status' => '200', 'data' => "Usuario Registrado, por favor verifique su correo"]);
    }

    public function login(Request $request) {
        $usuario =  User::where('email','=', $request->email)->first();
        if(!$usuario)
        {
            return response()
            ->json(['status' => '401', 'data' => "Usuario o contraseña Incorrecta"]);
        }

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $remember = $request->remember;

        if(!auth()->attempt($loginData,$remember)) {
            return response()
            ->json(['status' => '401', 'data' => "Usuario o contraseña Incorrecta"]);
        }

        $user = auth()->user();
        $userRole = $user->user_type;
        $accessToken = $user->createToken($user->email.'-'.now(),[$userRole]);
        $usuario->token = $accessToken->accessToken;
        $usuario->save();

        $new_user =  User::where('email','=', $user->email)->first();
        
        return response()
                    ->json(['status' => '200', 'data' => $this->userToStandar($new_user)]);    
    }

    public function adminRegister(Request $request) {
        $usuario =  User::where('email','=', $request->email)->first();
        if($usuario)
        {
            return response()
            ->json(['status' => '500', 'data' => "El usuario ya existe"]);
        }

        $validateData = $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellido' => 'required|regex:/^[\pL\s\-]+$/u',
            'email'=>'email|required|unique:users',
            'rol' => 'string|in:Admin,Profesor,Acudiente'
        ]);

        $password = $this->generatePassword();
        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        if($request->rol){
            $user_type = ucwords(strtolower($request->rol));
            $user->user_type = $user_type;
        }
        Mail::to($user->email)->send(new MessageStatus($password));
        $user->save();
        event(new Registered($user));

        $new_user =  User::where('email','=', $request->email)->first();

        return response()
                    ->json(['status' => '200', 'data' => "Usuario Registrado con éxito"]);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }

    public function users() {
        $users = User::all();
        return $users;
    }

    private function userToStandar($user)
    {
        $usuarioStandar = [
            'email' => $user->email,
            'provider' => $user->provider,
            'id' => $user->id,
            'token' => $user->token,
            'idToken' => $user->id_token,
            'name' => $user->nombre,
            'lastName' => $user->apellido,
            'rol' => $user->user_type
        ]; 
        return $usuarioStandar;
    }

    public function generatePassword() {
        $cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $cadena_base .= '0123456789';
        $password = '';
        $limite = strlen($cadena_base) - 1;

        for ($i=0; $i < 8; $i++)
            $password .= $cadena_base[rand(0, $limite)];

        return $password;
    }

    public function update(Request $request)
    {
        $usuario = User::where('email',$request->email)->update([
            "nombre" => $request->nombre,
            "apellido" => $request->apellido,
            "user_type" => $request->rol
        ]);
        return response()
            ->json(['status' => '200']);
    }

    public function destroy($id) {
        $usuario = User::where('id','=',$id);
        $usuario->delete();
        return response()
            ->json(['status' => '200']);
    }
}

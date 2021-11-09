<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class UserActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_active = json_decode(User::select('is_active')->where('email','=',$request->email)->first());
                
        if($user_active->is_active===0) {
            return response()
                    ->json(['status' => '401', 'data' => 'Su correo no ha sido verificado']);
        }
        return $next($request);
    }
}

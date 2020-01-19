<?php

namespace App\Http\Middleware;

use Closure;

class MobileAuth
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next)
  {
    $auth = auth();
    if (!$auth->user()->active) {
      return response()->json([
        'status'=>'error',
        'error'=>'activate',
        'message'=>'Perangkat belum aktif'
      ],401);
    }
    if (!$auth->user()->changed_password) {
      return response()->json([
        'status'=>'error',
        'error'=>'new_password',
        'message'=>'Password belum diganti'
      ],401);
    }
    return $next($request);
  }
}

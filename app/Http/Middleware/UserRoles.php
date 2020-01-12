<?php

namespace App\Http\Middleware;

use Closure;

class UserRoles
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
    $roles = array_slice(func_get_args(), 2);
    $user = \Auth::user();
    foreach ($roles as $role) {
      if ($user->role == $role) {
        return $next($request);
      }
    }
    return redirect()->route('login');
  }
}

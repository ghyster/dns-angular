<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		
		if ($this->auth->guest()){
			$authtype=\Config::get('app.authtype');

			if ($request->ajax()){
				return response('Unauthorized.', 401);
			}else if ($authtype == "google") {
				return redirect()->guest('login/google');
			}else if ($authtype == "saml"){
				return redirect()->guest('saml2/login');
			}else{
				return response('Unauthorized.', 401);
			}

		}

		return $next($request);
	}

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ActiveCheck
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->check() &&  $this->auth->user()->status == 1 ) {
            return $next($request);
        }

        return response()->json([
            "success" => false,
            "message" => "Your account is suspended,Please Call to support"
        ]);

    }
}
<?php
namespace Suarez\StatamicUtmParameters\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Suarez\StatamicUtmParameters\Facades\UtmParameter;

class CheckUtmParameter
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldAcceptUtmParameter($request)) {
            UtmParameter::boot($request);
        }

        return $next($request);
    }

    /**
     * Determines whether the given request/response pair should accept UTM-Parameters.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function shouldAcceptUtmParameter(Request $request)
    {
        return $request->isMethod('GET');
    }
}

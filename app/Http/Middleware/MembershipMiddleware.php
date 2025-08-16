<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MembershipMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if($user->referred_id != null) {
            $parent = User::find($user->referred_id);

            if (!$parent || !$parent->isMember()) {
                return $this->error([], 'Membership required', 200);
            }

            if ($parent->isExpired()) {
                return $this->error([], 'Membership expired', 200);
            }

            return $next($request);

        }
        

        if (!$user || !$user->isMember()) {
            return $this->error([], 'Membership required', 200);
        }

        if ($user->isExpired()) {
            return $this->error([], 'Membership expired', 200);
        }

        return $next($request);
    }
}

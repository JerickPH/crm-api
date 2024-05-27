<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimulateAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('SimulateAuth middleware triggered');
        // Simulate authentication here
        return $next($request);
    }
}

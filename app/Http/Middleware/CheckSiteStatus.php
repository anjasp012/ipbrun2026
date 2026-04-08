<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't check for admin routes, start tool, or midtrans callback
        if ($request->is('admin*') || 
            $request->is('start*') || 
            $request->is('trigger-start*') || 
            $request->is('payments/midtrans-callback*')) {
            return $next($request);
        }

        $isRunning = \App\Models\Setting::getValue('is_running', '0') === '1';

        if (!$isRunning && !$request->is('/')) {
            return redirect('/');
        }

        return $next($request);
    }
}

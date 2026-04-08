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
        // Don't check for admin routes, trigger tool, or midtrans callback
        if ($request->is('admin*') || 
            $request->is('trigger-start*') || 
            $request->is('payments/midtrans-callback*')) {
            return $next($request);
        }

        // Allow Home Page (it handles its own view logic for coming soon/countdown)
        if ($request->is('/')) {
            return $next($request);
        }

        // Check if registration is running (Master Switch)
        $isRunning = \App\Models\Setting::getValue('is_running', '0') === '1';
        if (!$isRunning) {
            return redirect('/');
        }

        // Check for Registration Schedule
        $startTimeStr = \App\Models\Setting::getValue('ticket_sale_start');
        if (!$startTimeStr) {
            return redirect('/');
        }

        try {
            $startTime = \Carbon\Carbon::parse($startTimeStr);
            if ($startTime->isFuture()) {
                // If we are still in countdown period, block other pages
                return redirect('/');
            }
        } catch (\Exception $e) {
            return redirect('/');
        }

        return $next($request);
    }
}

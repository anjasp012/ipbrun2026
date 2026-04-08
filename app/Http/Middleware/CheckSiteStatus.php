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

        // Allow Home Page and Login Page
        if ($request->path() === '/' || $request->path() === '' || $request->is('login*')) {
            return $next($request);
        }

        // Check if registration is running (Master Switch)
        // We use Setting::where instead of getValue to be 100% sure about the query
        $isRunning = \App\Models\Setting::where('key', 'is_running')->first()?->value === '1';
        if (!$isRunning) {
            return redirect('/');
        }

        // Check for Registration Schedule
        $startTimeStr = \App\Models\Setting::where('key', 'ticket_sale_start')->first()?->value;
        if ($startTimeStr) {
            try {
                // Ensure we compare using Asia/Jakarta to match user's local context
                $now = \Carbon\Carbon::now('Asia/Jakarta');
                $startTime = \Carbon\Carbon::parse($startTimeStr, 'Asia/Jakarta');
                
                if ($now->lessThan($startTime)) {
                    // It's still in countdown period, block access to internal pages
                    return redirect('/');
                }
            } catch (\Exception $e) {
                // If invalid date format, block to be safe
                return redirect('/');
            }
        } else {
            // No schedule set yet, block access
            return redirect('/');
        }

        return $next($request);
    }
}

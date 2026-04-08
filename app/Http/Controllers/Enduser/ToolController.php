<?php

namespace App\Http\Controllers\Enduser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Carbon\Carbon;

class ToolController extends Controller
{
    public function startPage()
    {
        $isRunning = Setting::getValue('is_running', '0') === '1';
        if ($isRunning) {
            return redirect('/');
        }
        return view('pages.enduser.start');
    }

    public function triggerStart(Request $request)
    {
        if ($request->password !== 'IpbRun2026#') {
            return response()->json(['success' => false, 'message' => 'Password salah!'], 403);
        }

        $isRunning = Setting::getValue('is_running', '0') === '1';
        if ($isRunning) {
            return response()->json(['success' => false, 'message' => 'Sistem sudah aktif!'], 403);
        }

        // Setting: 24h from now (+3 seconds for the countdown room)
        $startTime = Carbon::now('Asia/Jakarta')->addHours(24)->addSeconds(3);

        Setting::updateOrCreate(
            ['key' => 'ticket_sale_start'],
            ['value' => $startTime->toDateTimeString()]
        );

        // Disable maintenance mode
        Setting::updateOrCreate(
            ['key' => 'is_running'],
            ['value' => '1']
        );

        return response()->json([
            'success' => true,
            'redirect' => url('/')
        ]);
    }
}

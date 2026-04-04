<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('pages.admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        // Handle checkbox for notification active
        if (!isset($data['wa_notification_active'])) {
             $data['wa_notification_active'] = '0';
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}

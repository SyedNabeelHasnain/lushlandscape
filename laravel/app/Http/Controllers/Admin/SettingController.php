<?php

namespace App\Http\Controllers\Admin;

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $settings = Setting::orderBy('sort_order')->get()->groupBy('group');

        // Pre-load MediaAsset models for any media-type setting that has a value
        $mediaAssets = [];
        foreach ($settings->flatten() as $setting) {
            if ($setting->type === 'media' && $setting->value) {
                $mediaAssets[$setting->key] = MediaAsset::find((int) $setting->value);
            }
        }

        return \Illuminate\Support\Facades\View::make('admin.settings.index', compact('settings', 'mediaAssets'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        $allowedKeys = Setting::pluck('key')->toArray();

        // Collect all boolean-type setting keys so we can default missing (unchecked) ones to '0'
        $booleanKeys = Setting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $boolKey) {
            if (! array_key_exists($boolKey, $data)) {
                $data[$boolKey] = '0';
            }
        }

        // Batch update: collect all valid updates and execute in a single transaction
        $updates = [];
        foreach ($data as $key => $value) {
            if (! in_array($key, $allowedKeys, true)) {
                continue;
            }
            $updates[] = [
                'key' => $key,
                'value' => $value ?? '',
            ];
        }

        if (! empty($updates)) {
            // Use upsert for batch update — single query instead of N queries
            Setting::upsert(
                $updates,
                ['key'], // unique columns
                ['value'] // columns to update
            );
        }

        Setting::flushCache();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Settings saved successfully.');
        }

        return \Illuminate\Support\Facades\Redirect::back()->with('success', 'Settings saved successfully.');
    }
}

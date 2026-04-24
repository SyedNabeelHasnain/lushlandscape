<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = Webhook::latest()->paginate(20);

        return View::make('admin.webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        return View::make('admin.webhooks.form', ['webhook' => new Webhook]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'event' => 'required|string|max:255',
            'secret' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'timeout' => 'required|integer|min:1|max:30',
            'retry_count' => 'required|integer|min:0|max:5',
        ]);

        // Default to true if not present (unchecked)
        $validated['is_active'] = $request->has('is_active');

        // Simple header key/value pair parsing from a JSON string input (or form array if needed)
        if ($request->filled('headers_json')) {
            $validated['headers'] = json_decode($request->input('headers_json'), true);
        }

        Webhook::create($validated);

        return redirect()->route('admin.webhooks.index')->with('success', 'Webhook created.');
    }

    public function edit(Webhook $webhook)
    {
        return View::make('admin.webhooks.form', compact('webhook'));
    }

    public function update(Request $request, Webhook $webhook)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'event' => 'required|string|max:255',
            'secret' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'timeout' => 'required|integer|min:1|max:30',
            'retry_count' => 'required|integer|min:0|max:5',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->filled('headers_json')) {
            $validated['headers'] = json_decode($request->input('headers_json'), true);
        } else {
            $validated['headers'] = null;
        }

        $webhook->update($validated);

        return redirect()->route('admin.webhooks.index')->with('success', 'Webhook updated.');
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return redirect()->route('admin.webhooks.index')->with('success', 'Webhook deleted.');
    }
}

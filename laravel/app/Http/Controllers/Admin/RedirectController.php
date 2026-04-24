<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect as LaravelRedirect;
use Illuminate\Support\Facades\View;

class RedirectController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $redirects = Redirect::orderByDesc('created_at')->paginate(20);

        return View::make('admin.redirects.index', compact('redirects'));
    }

    public function create()
    {
        return View::make('admin.redirects.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects,old_url',
            'new_url' => ['required', 'string', 'max:500', 'regex:/^\//'],
            'status_code' => 'required|in:301,302',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $redirect = Redirect::create($validated);
        Cache::forget('redirects_map');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Redirect created.', [], route('admin.redirects.edit', $redirect));
        }

        return LaravelRedirect::route('admin.redirects.index')
            ->with('success', 'Redirect created.');
    }

    public function edit(Redirect $redirect)
    {
        return View::make('admin.redirects.form', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect)
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:500|unique:redirects,old_url,'.$redirect->id,
            'new_url' => ['required', 'string', 'max:500', 'regex:/^\//'],
            'status_code' => 'required|in:301,302',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $redirect->update($validated);
        Cache::forget('redirects_map');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Redirect updated.');
        }

        return LaravelRedirect::route('admin.redirects.index')
            ->with('success', 'Redirect updated.');
    }

    public function destroy(Request $request, Redirect $redirect)
    {
        $redirect->delete();
        Cache::forget('redirects_map');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Redirect deleted.');
        }

        return LaravelRedirect::route('admin.redirects.index')->with('success', 'Redirect deleted.');
    }
}

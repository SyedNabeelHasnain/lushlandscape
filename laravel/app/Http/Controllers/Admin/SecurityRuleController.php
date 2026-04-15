<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\SecurityRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class SecurityRuleController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $rules = SecurityRule::orderByDesc('created_at')->paginate(20);

        return View::make('admin.security-rules.index', compact('rules'));
    }

    public function create()
    {
        return View::make('admin.security-rules.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:ip,country,region',
            'value' => 'required|string|max:255',
            'action' => 'required|in:allow,block',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $securityRule = SecurityRule::create($validated);
        Cache::forget('security_blocked_ips');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Security rule created.', [], route('admin.security-rules.edit', $securityRule));
        }

        return Redirect::route('admin.security-rules.index')
            ->with('success', 'Security rule created.');
    }

    public function edit(SecurityRule $securityRule)
    {
        return View::make('admin.security-rules.form', ['rule' => $securityRule]);
    }

    public function update(Request $request, SecurityRule $securityRule)
    {
        $validated = $request->validate([
            'type' => 'required|in:ip,country,region',
            'value' => 'required|string|max:255',
            'action' => 'required|in:allow,block',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $securityRule->update($validated);
        Cache::forget('security_blocked_ips');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Security rule updated.');
        }

        return Redirect::route('admin.security-rules.index')
            ->with('success', 'Security rule updated.');
    }

    public function destroy(Request $request, SecurityRule $securityRule)
    {
        $securityRule->delete();
        Cache::forget('security_blocked_ips');

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Security rule deleted.');
        }

        return Redirect::route('admin.security-rules.index')->with('success', 'Security rule deleted.');
    }
}

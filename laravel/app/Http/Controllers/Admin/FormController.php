<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class FormController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $forms = Form::withCount('submissions')->orderBy('name')->paginate(20);

        return View::make('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return View::make('admin.forms.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:forms,slug',
            'form_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'success_message' => 'nullable|string',
            'email_to' => 'nullable|string',
            'email_cc' => 'nullable|string',
            'email_bcc' => 'nullable|string',
            'requires_email_verification' => 'boolean',
            'honeypot_enabled' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['requires_email_verification'] = $request->boolean('requires_email_verification');
        $validated['honeypot_enabled'] = $request->boolean('honeypot_enabled');
        foreach (['email_to', 'email_cc', 'email_bcc'] as $emailField) {
            if (! empty($validated[$emailField])) {
                $val = $validated[$emailField];
                $validated[$emailField] = is_array($val) ? $val : array_map('trim', explode(',', (string) $val));
            }
        }

        $form = Form::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Form created.', [], route('admin.forms.edit', $form));
        }

        return Redirect::route('admin.forms.index')
            ->with('success', 'Form created.');
    }

    public function edit(Form $form)
    {
        $form->load('fields');

        return View::make('admin.forms.form', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:forms,slug,'.$form->id,
            'form_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'success_message' => 'nullable|string',
            'email_to' => 'nullable|string',
            'email_cc' => 'nullable|string',
            'email_bcc' => 'nullable|string',
            'requires_email_verification' => 'boolean',
            'honeypot_enabled' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['requires_email_verification'] = $request->boolean('requires_email_verification');
        $validated['honeypot_enabled'] = $request->boolean('honeypot_enabled');
        foreach (['email_to', 'email_cc', 'email_bcc'] as $emailField) {
            if (! empty($validated[$emailField])) {
                $val = $validated[$emailField];
                $validated[$emailField] = is_array($val) ? $val : array_map('trim', explode(',', (string) $val));
            }
        }

        $form->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Form updated.');
        }

        return Redirect::route('admin.forms.index')
            ->with('success', 'Form updated.');
    }

    public function destroy(Request $request, Form $form)
    {
        if ($form->submissions()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError('Cannot delete form with existing submissions.');
            }

            return Redirect::back()->with('error', 'Cannot delete form with existing submissions.');
        }

        $form->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Form deleted.');
        }

        return Redirect::route('admin.forms.index')->with('success', 'Form deleted.');
    }
}

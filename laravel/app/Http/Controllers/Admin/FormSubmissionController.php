<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class FormSubmissionController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = FormSubmission::with('form')->orderByDesc('created_at');

        if ($request->filled('form')) {
            $query->where('form_id', $request->form);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate(20);
        $forms = Form::orderBy('name')->pluck('name', 'id');

        return View::make('admin.submissions.index', compact('submissions', 'forms'));
    }

    public function show(FormSubmission $submission)
    {
        $submission->load('form.fields');

        return View::make('admin.submissions.show', compact('submission'));
    }

    public function update(Request $request, FormSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
        ]);

        $submission->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Status updated.');
        }

        return Redirect::back()->with('success', 'Submission status updated.');
    }

    public function destroy(Request $request, FormSubmission $submission)
    {
        $submission->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Submission deleted.');
        }

        return Redirect::route('admin.submissions.index')->with('success', 'Submission deleted.');
    }
}

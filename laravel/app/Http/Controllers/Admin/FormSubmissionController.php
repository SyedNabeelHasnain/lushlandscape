<?php

namespace App\Http\Controllers\Admin;

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

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

        return \Illuminate\Support\Facades\View::make('admin.submissions.index', compact('submissions', 'forms'));
    }

    public function show(FormSubmission $submission)
    {
        $submission->load('form.fields');

        return \Illuminate\Support\Facades\View::make('admin.submissions.show', compact('submission'));
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

        return \Illuminate\Support\Facades\Redirect::back()->with('success', 'Submission status updated.');
    }

    public function destroy(Request $request, FormSubmission $submission)
    {
        $submission->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Submission deleted.');
        }

        return \Illuminate\Support\Facades\Redirect::route('admin.submissions.index')->with('success', 'Submission deleted.');
    }
}

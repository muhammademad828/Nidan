<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePolicyRequest;
use App\Models\Policy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PolicyController extends Controller
{
    public function index(): View
    {
        $policies = Policy::orderBy('sort_order')->get();
        return view('admin.policies.index', compact('policies'));
    }

    public function edit(Policy $policy): View
    {
        return view('admin.policies.edit', compact('policy'));
    }

    public function update(UpdatePolicyRequest $request, Policy $policy): RedirectResponse
    {
        $policy->update($request->validated());

        return back()->with('success', 'Policy updated.');
    }

    public function editTerms(): View
    {
        $policy = Policy::firstOrCreate(
            ['type' => 'terms'],
            [
                'title_ar' => 'الشروط والأحكام',
                'title_en' => 'Terms & Conditions',
                'content_ar' => 'اكتب الشروط هنا...',
                'content_en' => 'Write terms here...'
            ]
        );
        
        return view('admin.policies.edit', compact('policy'));
    }
}

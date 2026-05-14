<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSlide;
use App\Http\Requests\Admin\UpsertEventSlideRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventSlideController extends Controller
{
    public function index()
    {
        $slides = EventSlide::orderBy('sort_order')->get();
        return view('admin.event-slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.event-slides.create');
    }

    public function store(UpsertEventSlideRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('media_file')) {
            $path = $request->file('media_file')->store('events', 'public');
            $data['media_url'] = Storage::url($path);
        }

        $data['is_active'] = $request->has('is_active');

        EventSlide::create($data);

        return redirect()->route('admin.event-slides.index')->with('success', 'Slide created successfully.');
    }

    public function edit(EventSlide $eventSlide)
    {
        return view('admin.event-slides.edit', compact('eventSlide'));
    }

    public function update(UpsertEventSlideRequest $request, EventSlide $eventSlide)
    {
        $data = $request->validated();

        if ($request->hasFile('media_file')) {
            $path = $request->file('media_file')->store('events', 'public');
            $data['media_url'] = Storage::url($path);
        }

        $data['is_active'] = $request->has('is_active');

        $eventSlide->update($data);

        return redirect()->route('admin.event-slides.index')->with('success', 'Slide updated successfully.');
    }

    public function destroy(EventSlide $eventSlide)
    {
        $eventSlide->delete();
        return redirect()->route('admin.event-slides.index')->with('success', 'Slide deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:event_slides,id'
        ]);

        foreach ($request->order as $index => $id) {
            EventSlide::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DoctorRecord;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    public function index()
    {
        $doctors = DoctorRecord::all();
        return view('doctor_records.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctor_records.create');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|in:Dr.,Mr.,Mrs.,Ms.',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:doctor_records',
            'password' => 'required|string|min:8',
            'address' => 'required|string',
        ]);
        
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'app/public');
            $validated['profile_image'] = $imagePath;
        }

        DoctorRecord::create($validated);
        
        return redirect()->route('doctor_records.index')
            ->with('success', 'Doctor record created successfully.');
    }

    public function show($id)
{
    $doctor = DoctorRecord::findOrFail($id);
    return view('doctor_records.show', compact('doctor'));
}


public function edit(DoctorRecord $doctorRecord)
{
    return view('doctor_records.edit', compact('doctorRecord'));
}

public function update(Request $request, DoctorRecord $doctorRecord)
{
    $validated = $request->validate([
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'title' => 'required|in:Dr.,Mr.,Mrs.,Ms.',
        'full_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|string|email|max:255|unique:doctor_records,email,' . $doctorRecord->id,
        'password' => 'nullable|string|min:8',
        'address' => 'required|string',
    ]);

    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        $validated['profile_image'] = $imagePath;
    }

    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $doctorRecord->update($validated);

    return redirect()->route('doctor_records.index')
        ->with('success', 'Doctor record updated successfully.');
}


    public function destroy(DoctorRecord $doctorRecord)
    {
        $doctorRecord->delete();

        return redirect()->route('doctor_records.index')
            ->with('success', 'Doctor record deleted successfully.');
    }
}

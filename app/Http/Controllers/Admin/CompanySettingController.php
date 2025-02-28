<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    /**
     * [Admin] Display a listing of company settings.
     */
    public function index()
    {
        $companySettings = CompanySetting::all();
        return response()->json($companySettings);
    }

    /**
     * [Admin] Store a newly created company setting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if exists
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $companySetting = CompanySetting::create($validated);
        return response()->json($companySetting, 201);
    }

    /**
     * [Admin] Display the specified company setting.
     */
    public function show(CompanySetting $companySetting)
    {
        return response()->json($companySetting);
    }

    /**
     * [Admin] Update the specified company setting.
     */
    public function update(Request $request, CompanySetting $companySetting)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if exists and delete old file
        if ($request->hasFile('logo')) {
            if ($companySetting->logo) {
                Storage::disk('public')->delete($companySetting->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $companySetting->update($validated);
        return response()->json($companySetting);
    }

    /**
     * [Admin] Remove the specified company setting.
     */
    public function destroy(CompanySetting $companySetting)
    {
        // Delete the associated logo file
        if ($companySetting->logo) {
            Storage::disk('public')->delete($companySetting->logo);
        }

        $companySetting->delete();
        return response()->json(null, 204);
    }
}

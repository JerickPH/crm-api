<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;

class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    public function store(Request $request)
    {
        Log::info('Request Data: ' . json_encode($request->all()));

        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png',
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'telephone_number' => 'nullable',
            'website' => 'nullable|url',
            'about_company' => 'nullable',
            'street_address' => 'required',
            'city' => 'required',
            'state_province' => 'required',
            'zipcode' => 'required',
            'country' => 'required',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'skype' => 'nullable',
            'whatsapp' => 'nullable',
            'instagram' => 'nullable',
            'status' => 'required|in:Active,Private,Inactive'
        ]);

        try {
            $company = Company::create([
                'profile_image' => $request->file('profile_image') ? $request->file('profile_image')->store('profile_images') : null,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'telephone_number' => $request->input('telephone_number'),
                'website' => $request->input('website'),
                'about_company' => $request->input('about_company'),
                'street_address' => $request->input('street_address'),
                'city' => $request->input('city'),
                'state_province' => $request->input('state_province'),
                'zipcode' => $request->input('zipcode'),
                'country' => $request->input('country'),
                'facebook' => $request->input('facebook'),
                'twitter' => $request->input('twitter'),
                'linkedin' => $request->input('linkedin'),
                'skype' => $request->input('skype'),
                'whatsapp' => $request->input('whatsapp'),
                'instagram' => $request->input('instagram'),
                'status' => $request->input('status')
            ]);

             Log::info('Company created: ', $company->toArray());

            // Return response
            return response()->json(['message' => 'Company created successfully', 'data' => $company], 201);
        } catch (\Throwable $th) {
            Log::error("Error creating company: " . $th->getMessage());
            return response()->json(['message' => 'Error processing company creation'], 500);
        }
    }


    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png',
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'telephone_number' => 'nullable',
            'website' => 'nullable|url',
            'about_company' => 'nullable',
            'street_address' => 'required',
            'city' => 'required',
            'state_province' => 'required',
            'zipcode' => 'required',
            'country' => 'required',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'skype' => 'nullable',
            'whatsapp' => 'nullable',
            'instagram' => 'nullable',
            'status' => 'required|in:Active,Private,Inactive'
        ]);

        $updated = false;

        foreach ($request->all() as $key => $value) {
            if ($company->$key !== $value) {
                $company->$key = $value;
                $updated = true;
            }
        }

        if ($updated) {
            $company->save();
            Log::info('Company updated: ', $company->toArray());

            $userId = auth()->check() ? auth()->user()->id : null;

            Activity::create([
                'user_id' => $userId,
                'action' => 'update',
                'subject_type' => Company::class,
                'subject_id' => $company->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            Log::info('No changes detected.');
        }

        return response()->json(['message' => 'Company updated successfully', 'data' => $company], 200);
    }

    public function destroy(Company $company)
    {
        $userId = auth()->check() ? auth()->user()->id : null;

        Activity::create([
            'user_id' => $userId,
            'action' => 'delete',
            'subject_type' => Company::class,
            'subject_id' => $company->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $company->delete();

        return response()->json(['message' => 'Company deleted']);
    }
}

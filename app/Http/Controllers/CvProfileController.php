<?php

namespace App\Http\Controllers;

use App\Models\CvTemplate;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CvProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->loadCount(['experiences', 'educations', 'certificates', 'skills']);
        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);
        $templates = CvTemplate::availableForUser($user->id)->orderBy('name')->get();
        return view('cv.profile.edit', compact('profile', 'user', 'templates'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'github' => ['nullable', 'string', 'max:255'],
            'driver_license' => ['nullable', 'boolean'],
            'driver_license_type' => ['nullable', 'string', 'max:50'],
            'template' => [
                'required',
                'string',
                'max:50',
                Rule::exists('cv_templates', 'slug')->where(function ($query) {
                    $query->where('is_system', true)->orWhere('user_id', Auth::id());
                }),
            ],
        ]);
        $data['driver_license'] = $request->boolean('driver_license');
        if (!$data['driver_license']) {
            $data['driver_license_type'] = null;
        }

        $user = Auth::user();
        $profile = $user->profile;
        if (!$profile) {
            $profile = new Profile(['user_id' => $user->id]);
        }
        $profile->fill($data);
        $profile->user_id = $user->id;
        $profile->save();

        return redirect()->route('cv.profile.edit')->with('status', 'Perfil actualizado');
    }
}

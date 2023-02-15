<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateApplicationSettingsRequest;
use App\Models\ApplicationSettings;

class ApplicationSettingsController extends Controller
{
    public function index(ApplicationSettings $settings)
    {
        return view('settings.index');
    }

    public function update(UpdateApplicationSettingsRequest $request)
    {
        ApplicationSettings::find(1)->update($request->validated());

        cache()->forget('settings');

		if ($request->ajax()) {
			return response()->json();
		}

		return to_route('settings.index');
    }
}

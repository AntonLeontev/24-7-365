<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateApplicationSettingsRequest;
use App\Models\ApplicationSettings;

class ApplicationSettingsController extends Controller
{
    public function update(UpdateApplicationSettingsRequest $request)
    {
        ApplicationSettings::find(1)->update($request->validated());

		cache()->forget('settings');

        return response()->json();
    }
}

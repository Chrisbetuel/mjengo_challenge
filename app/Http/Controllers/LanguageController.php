<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale');

        // Validate locale
        if (in_array($locale, ['en', 'sw'])) {
            Session::put('locale', $locale);
        }

        return Redirect::back();
    }
}

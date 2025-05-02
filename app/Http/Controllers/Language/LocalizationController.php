<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Cookie;

class LocalizationController extends Controller
{
    protected LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function localization(string $locale = 'bn')
    {
        $language = $this->languageService->getLanguageByCode($locale);

        if($language)
        {
            Cookie::queue( Cookie::forever('locale', $locale) );
            return redirect()->back();
        }
        else{
            $response = [
                'success' => false,
                'message' => 'Language not found',
                'data' => []
            ];
            $httpStatus = 404;
            return response()->json($response, $httpStatus);
        }
    }
}

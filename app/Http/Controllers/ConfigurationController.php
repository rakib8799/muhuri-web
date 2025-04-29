<?php

namespace App\Http\Controllers;

use App\Http\Requests\Configuration\UpdateSystemSettingRequest;
use Inertia\Inertia;
use App\Constants\Constants;
use App\Services\CountryService;
use App\Services\LanguageService;
use App\Services\ConfigurationService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\Configuration\UpdateConfigurationRequest;

class ConfigurationController extends Controller implements HasMiddleware
{
    protected ConfigurationService $configurationService;
    protected CountryService $countryService;
    protected LanguageService $languageService;

    public function __construct(ConfigurationService $configurationService, CountryService $countryService, LanguageService $languageService)
    {
        $this->configurationService = $configurationService;
        $this->countryService = $countryService;
        $this->languageService = $languageService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-configuration', only: ['details']),
            new Middleware('permission:can-edit-configuration', only: ['basicInfo', 'updateBasicInfo', 'contactInfo', 'updateContactInfo']),
        ];
    }

    public function details()
    {
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $languages = $this->languageService->getLanguages();
        $breadcrumbs = Breadcrumbs::generate('settings', $configuration);
        $responseData = [
            'configuration' => $configuration,
            'languages' => $languages,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.configuration.details'),
        ];
        return Inertia::render('Configuration/Details', $responseData);
    }

    public function basicInfo()
    {
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $breadcrumbs = Breadcrumbs::generate('settingsBasicInfo', $configuration);
        $countries = $this->countryService->getCountries();
        $responseData = [
            'countries' => $countries,
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.configuration.basicInfo'),
        ];
        return Inertia::render('Configuration/BasicInfo', $responseData);
    }

    public function updateBasicInfo(UpdateConfigurationRequest $request)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->configurationService->updateConfiguration($validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.configuration.update.basicInfo.success') : __('message.custom.configuration.basicInfo.error');
        return Redirect::route('configurations.details')->with($status, $message);
    }

    public function contactInfo()
    {
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $breadcrumbs = Breadcrumbs::generate('settingsContactInfo', $configuration);
        $responseData = [
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.configuration.contactInfo'),
        ];
        return Inertia::render('Configuration/ContactInfo', $responseData);
    }

    public function updateContactInfo(UpdateConfigurationRequest $request)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->configurationService->updateConfiguration($validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.configuration.update.contactInfo.success') : __('message.custom.configuration.contactInfo.error');
        return Redirect::route('configurations.details')->with($status, $message);
    }

    public function systemSettings()
    {
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $breadcrumbs = Breadcrumbs::generate('systemSetting', $configuration);
        $responseData = [
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.configuration.systemSettings'),
        ];
        return Inertia::render('Configuration/SystemSetting', $responseData);
    }

    public function updateSystemSetting(UpdateSystemSettingRequest $request)
    {
        $validatedData = $request->validated();

        $optionName = $validatedData['option_name'];

        $isUpdated = $this->configurationService->changeStatus($optionName);

        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;

        if ($isUpdated) {
            $newValue = $this->configurationService->getConfiguration($optionName);
            $message = $newValue == 1 ? 'Permission is activated' : 'Permission is deactivated';
        } else {
            $message = 'Permission status could not be changed';
        }
        
        return Redirect::back()->with($status, $message);
    }
}

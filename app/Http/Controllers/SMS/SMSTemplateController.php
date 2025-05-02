<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\SMS\SMSTemplate\CreateSMSTemplateRequest;
use App\Http\Requests\SMS\SMSTemplate\UpdateSMSTemplateRequest;
use App\Models\SMS\SMSTemplate;
use App\Services\Core\HelperService;
use App\Services\SMS\SMSTemplateService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SMSTemplateController extends Controller implements HasMiddleware
{
    protected SMSTemplateService $smsTemplateService;

    public function __construct(SMSTemplateService $smsTemplateService)
    {
        $this->smsTemplateService = $smsTemplateService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-sms-template', only: ['index']),
            new Middleware('permission:can-create-sms-template', only: ['create','store']),
            new Middleware('permission:can-edit-sms-template', only: ['edit','update']),
            new Middleware('permission:can-delete-sms-template', only: ['destroy']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('smsTemplate');
        $smsTemplates = $this->smsTemplateService->all();
        $responseData = [
            'smsTemplates' => $smsTemplates,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.smsTemplate.index')
        ];

        return Inertia::render('SMS/SMSTemplate/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addSMSTemplate');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.smsTemplate.create')
        ];
        return Inertia::render('SMS/SMSTemplate/Create', $responseData);
    }

    public function store(CreateSMSTemplateRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['slug'] = HelperService::generateUniqueSlugFromTranslatedName($validatedData['name'], SMSTemplate::class);
        $isCreated = $this->smsTemplateService->create($validatedData);
        $status = $isCreated ? 'success' : 'error';
        $message = $isCreated ? __('message.custom.sms.template.store.success') : __('message.custom.sms.template.store.error');
        return redirect()->route('sms.templates.index')->with($status, $message);
    }

    public function edit(SMSTemplate $smsTemplate)
    {
        $breadcrumbs = Breadcrumbs::generate('editSMSTemplate', $smsTemplate);
        $responseData = [
            'smsTemplate' => $smsTemplate,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.smsTemplate.edit')
        ];

        return Inertia::render('SMS/SMSTemplate/Create', $responseData);
    }

    public function update(UpdateSMSTemplateRequest $request, SMSTemplate $smsTemplate)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->smsTemplateService->update($smsTemplate, $validatedData);
        $status = $isUpdated ? 'success' : 'error';
        $message = $isUpdated ?  __('message.custom.sms.template.update.success') : __('message.custom.sms.template.update.error');
        return redirect()->route('sms.templates.index')->with($status, $message);
    }

    public function destroy(SMSTemplate $smsTemplate)
    {
        $isDeleted = $this->smsTemplateService->delete($smsTemplate->id);
        $status = $isDeleted ? 'success' : 'error';
        $message = $isDeleted ?  __('message.custom.sms.template.destroy.success') : __('message.custom.sms.template.destroy.error');
        return redirect()->route('sms.templates.index')->with($status, $message);
    }

    public function changeStatus(Request $request, SMSTemplate $smsTemplate)
    {
        $smsTemplate = $this->smsTemplateService->changeStatus($smsTemplate, $request->is_active);
        $status =  $smsTemplate ? 'success' : 'error';
        $message = $smsTemplate ? ($smsTemplate->is_active ? __('message.custom.sms.template.changeStatus.activate') : __('message.custom.sms.template.changeStatus.deactivate')) : __('message.custom.sms.template.changeStatus.error');
        return redirect()->back()->with($status, $message);
    }
}

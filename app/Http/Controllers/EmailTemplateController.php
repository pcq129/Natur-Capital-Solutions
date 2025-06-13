<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ToasterService;
use App\Exceptions\Handler;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use App\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use App\Http\Requests\EmailTemplate\UpdateEmailTemplateRequest;
use App\Constants\EmailTemplateConstants as CONSTANTS;


// testing deps
use App\Mail\EmailTemplate as Template;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class EmailTemplateController extends Controller
{

    public function __construct(private ToasterService $toasterService, private EmailTemplateService $emailTemplateService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $action = $this->emailTemplateService->fetchTemplates($request);
                return ($action->data);
            } else {
                return view('Pages.EmailTemplates.index');
            }
        } catch (\Exception $e) {
            $message = CONSTANTS::FETCH_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Pages.EmailTemplates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmailTemplateRequest $request)
    {
        try {
            $newTemplate = $request->validated();
            $action = $this->emailTemplateService->createTemplate($newTemplate);
            $this->toasterService->toast($action);
            return redirect()->route('email-templates.index');
        } catch (\Exception $e) {
            $message = CONSTANTS::STORE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        $data = $emailTemplate;
        return view('Pages.EmailTemplates.update', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        try {
            $newEmailTemplate = $request->validated();
            $action = $this->emailTemplateService->updateTemplate($newEmailTemplate, $emailTemplate);
            $this->toasterService->toast($action);
            return redirect()->route('email-templates.index');
        } catch (\Exception $e) {
            $message = CONSTANTS::UPDATE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        try {
            $action = $this->emailTemplateService->deleteTemplate($emailTemplate);
            $this->toasterService->toast($action);
            return redirect()->back();
        } catch (\Exception $e) {
            $message = CONSTANTS::DELETE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $message);
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ToasterService;
use App\Exceptions\Handler;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use App\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use App\Http\Requests\EmailTemplate\UpdateEmailTemplateRequest;


// testing deps
use App\Mail\EmailTemplate as Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            $message = "Error while fetching Email Templates";
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
        $newTemplate = $request->validated();
        $action = $this->emailTemplateService->createTemplate($newTemplate);
        $this->toasterService->toast($action);
        return redirect()->route('email-templates.index');
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
        $newEmailTemplate = $request->validated();
        // dd($newEmailTemplate);
        $action = $this->emailTemplateService->updateTemplate($newEmailTemplate, $emailTemplate);
        $this->toasterService->toast($action);
        return redirect()->route('email-templates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        $action = $this->emailTemplateService->deleteTemplate($emailTemplate);
        $this->toasterService->toast($action);
        return redirect()->back();
    }


    // below is the reference method for future usage.

    // the email content will be fetched dynamically from the database and
    // rendered. You will need to modify this method a bit to actually send the mail
    // refer laravel mail docs (v11).

    // for now just rendering it.
    public function sendmail(){
        // data should contain
        $emailText = EmailTemplate::find(21)->trixRender('EmailTemplateContent');
        $data = EmailTemplate::find(21);
        $user = 'Test User';
        return (new Template($data, $emailText, $user))->render();
    }
}

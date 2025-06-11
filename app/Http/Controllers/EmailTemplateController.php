<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Services\ToasterService;
use App\Exceptions\Handler;
use App\Services\EmailTemplateService;

class EmailTemplateController extends Controller
{

    public function __construct(private ToasterService $toasterService, private EmailTemplateService $emailTemplateService){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $action = null;
            if ($request->ajax()) {
                $action = $this->emailTemplateService->fetchEmailTemplates($request);
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
}

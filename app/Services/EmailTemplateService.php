<?php

namespace App\Services;

use App\Mail\EmailTemplate as Email;
use App\Models\EmailTemplate;
use App\Enums\Status;
use App\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use App\Services\DTO\ServiceResponse;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateService
{
    public function fetchTemplates($request): ServiceResponse
    {

        $query = EmailTemplate::query();

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        $templates = DataTables::of($query)
            ->addColumn('status', function ($row) {
                return $row->status == Status::ACTIVE->value ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('email-templates.edit', $row->id);
                $target = EmailTemplate::DELETE_MODAL_ID;
                return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $target]);
            })
            ->rawColumns(['actions'])
            ->make(true);

        return ServiceResponse::success("Email templates fetched successfully",  $templates);
    }


    public function deleteTemplate(EmailTemplate $emailTemplate): ServiceResponse
    {
        $result = $emailTemplate->delete();

        if ($result) {
            return ServiceResponse::success('Email template deleted successfully');
        }else{
            return ServiceResponse::error('Unable to delete Template');
        }
    }

    public function createTemplate($request): ServiceResponse
    {
        $newTemplateData = $request;

        EmailTemplate::create([
            'name' => $newTemplateData['name'],
            'subject' => $newTemplateData['subject'],
            'language'=> $newTemplateData['language'],
            'emailtemplate-trixFields' => $newTemplateData['emailtemplate-trixFields'],
            'send_to' => $newTemplateData['role'],
            'status' => Status::ACTIVE
        ]);

        return ServiceResponse::success('Template created successfully');
    }

    public function updateTemplate($request, EmailTemplate $emailTemplate): ServiceResponse
    {
        $emailTemplate->fill([
            "name" => $request['name'],
            "subject" => $request['subject'],
            "language" => $request['language'],
            "send_to" => $request['role'],
            "status" => $request['status'],
            "emailtemplate-trixFields" => $request['emailtemplate-trixFields'],
        ]);
        // dd($emailTemplate);

        if($emailTemplate->isDirty()){
            $emailTemplate->save();
            // dd($emailTemplate);
            return ServiceResponse::success('Template updated successfully');
        }else{
            return ServiceResponse::info('No changes detected');
        }
    }
}

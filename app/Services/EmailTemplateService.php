<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Constants\EmailTemplateConstants as CONSTANTS;
use App\Constants\AppConstants;
use App\Enums\Role;
use App\Exceptions\Handler;



class EmailTemplateService
{

    public function fetchTemplates($request): ServiceResponse
    {
        try {
            $query = EmailTemplate::query()->orderBy('id', 'DESC');

            if ($request->filled('status')) {
                $query->where('status', (int) $request->status);
            }

            $templates = DataTables::of($query)
                ->addColumn('status', function ($row) {
                    return $row->status == Status::ACTIVE->value ? 'Active' : 'Inactive';
                })
                // ->addColumn('send_to', function ($row) {
                //     return $row->send_to == Role::ADMIN ? 'Admin' : 'User';
                // })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('email-templates.edit', $row->id);
                    $action = AppConstants::VIEW_ICON;

                    $showViewButton = false;
                    $trixField = 'EmailTemplateContent';

                    if (method_exists($row, 'trix')) {
                        try {
                            $trixContent = $row->trixRender($trixField);
                            $showViewButton = !empty($trixContent);
                        } catch (\Throwable $e) {
                            $showViewButton = false;
                        }
                    }
                    return view('Partials.template', [
                        'edit' => $editUrl,
                        'row' => $row,
                        'action' => $action,
                        'showView' => $showViewButton,
                    ]);
                })

                ->rawColumns(['actions'])
                ->make(true);

            return ServiceResponse::success(CONSTANTS::FETCH_SUCCESS, $templates);
        } catch (\Exception $e) {
            $MESSAGE = CONSTANTS::FETCH_FAIL;
            $this->toasterService->exceptionToast($MESSAGE);
            Handler::logError($e, $e->getMessage());
            return ServiceResponse::error($MESSAGE);
        }
    }


    public function deleteTemplate(EmailTemplate $emailTemplate): ServiceResponse
    {
        try {
            $result = $emailTemplate->delete();
            if ($result) {
                return ServiceResponse::success(CONSTANTS::DELETE_SUCCESS);
            } else {
                return ServiceResponse::error(CONSTANTS::DELETE_FAIL);
            }
        } catch (\Exception $e) {
            $message = CONSTANTS::DELETE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $e->getMessage());
            return ServiceResponse::error($message);
        }
    }

    public function createTemplate($request): ServiceResponse
    {
        try {
            $newTemplateData = $request;
            EmailTemplate::create([
                'name' => $newTemplateData['name'],
                'subject' => $newTemplateData['subject'],
                'language' => $newTemplateData['language'],
                'emailtemplate-trixFields' => $newTemplateData['emailtemplate-trixFields'],
                // 'send_to' => $newTemplateData['role'],
                'status' => Status::ACTIVE
            ]);
            return ServiceResponse::success(CONSTANTS::STORE_SUCCESS);
        } catch (\Exception $e) {
            $message = CONSTANTS::STORE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $e->getMessage());
            return ServiceResponse::error($message);
        }
    }

    public function updateTemplate($request, EmailTemplate $emailTemplate): ServiceResponse
    {
        try {
            $emailTemplate->fill([
                "name" => $request['name'],
                "subject" => $request['subject'],
                "language" => $request['language'],
                // "send_to" => $request['role'],
                "status" => $request['status'],
                "emailtemplate-trixFields" => $request['emailtemplate-trixFields'],
            ]);
            if ($emailTemplate->isDirty()) {
                $emailTemplate->save();
                return ServiceResponse::success(CONSTANTS::UPDATE_SUCCESS);
            } else {
                return ServiceResponse::info(CONSTANTS::NO_CHANGE);
            }
        } catch (\Exception $e) {
            $message = CONSTANTS::UPDATE_FAIL;
            $this->toasterService->exceptionToast($message);
            Handler::logError($e, $e->getMessage());
            return ServiceResponse::error($message);
        }
    }


    // METHOD TO SEND EMAIL

    // public function sendMail($emailContent, $email, $dynamicData){
    //     Mail::to($email)->send(new Template($emailContent, $email, $dynamicData));
    // }
}

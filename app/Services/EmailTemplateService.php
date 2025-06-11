<?php

namespace App\Services;

use App\Mail\EmailTemplate as Email;
use App\Models\EmailTemplate;
use App\Enums\Status;
use App\Services\DTO\ServiceResponse;
use Yajra\DataTables\Facades\DataTables;

class EmailTemplateService
{
    public function fetchEmailTemplates($request): ServiceResponse
    {

        $query = EmailTemplate::query();

        if($request->filled('status')){
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
}

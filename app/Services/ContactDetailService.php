<?php

namespace App\Services;

use App\Enums\Status;
use App\Models\ContactDetail;
use App\Services\DTO\ServiceResponse;


class ContactDetailService{

    public function store(array $data): ServiceResponse{
        ContactDetail::create([
            'name'=>$data['name'],
            'contact_type'=>$data['contactType'],
            'action_url' => $data['actionUrl'],
            'priority' =>$data['priority'],
            'status' => Status::ACTIVE,
            'contact' => $data['contactInput'],
            'button_text'=>$data['buttonText']
        ]);
        return ServiceResponse::success("Contact Details added successfully");
    }

    public function update(array $data, ContactDetail $contactDetail): ServiceResponse{
        $contactDetail->fill([
            'name'=>$data['name'],
            'contact_type'=>$data['contactType'],
            'action_url' => $data['actionUrl'],
            'priority' =>$data['priority'],
            'status' => Status::ACTIVE,
            'contact' => $data['contactInput'],
            'button_text'=>$data['buttonText'],
            'status' => isset($data['status'])? Status::ACTIVE : Status::INACTIVE,
        ]);

        if ($contactDetail->isDirty()) {
            $contactDetail->save();
            return ServiceResponse::success('Contact Detail updated successfully');
        } else {
            return ServiceResponse::info('No changes detected');
        }
    }
}
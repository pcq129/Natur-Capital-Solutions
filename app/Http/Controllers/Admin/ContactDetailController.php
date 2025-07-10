<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactDetail;
use Illuminate\Http\Request;
use App\Constants\ContactDetailConstants;
use App\Enums\Status;
use Yajra\DataTables\DataTables;
use App\Enums\ContactType;
use App\Http\Requests\ContactDetail\createContactDetailRequest;
use App\Services\ContactDetailService;
use App\Services\ToasterService;
use App\Http\Requests\ContactDetail\UpdateContactDetailRequest;

class ContactDetailController extends Controller
{

    public function __construct(private ContactDetailService $contactDetailService, private ToasterService $toasterService){

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            // dd($request);
            $query = ContactDetail::query();

            if($request->filled('status')){
                $query->where('status', (int) $request->status);
            }

            $contactDetails = DataTables::of($query)
                ->addColumn('status', function ($row) {
                    return $row->status == Status::ACTIVE ? 'Active' : 'Inactive';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('contact-details.edit', $row->id);
                    $target = ContactDetailConstants::CONTACT_DETAIL_DELETE_MODAL_ID;
                    return view('Partials.actions', ['edit' => $editUrl,  'row' => $row, 'target' => $target])->render();
                })
                ->addColumn('contact_type', function ($row) {
                    if($row->contact_type== ContactType::EMAIL){
                        return 'Email';
                    }else{
                        return 'Phone';
                    }
                })
                ->rawColumns(['actions'])
                ->make(true);

                return $contactDetails;

            // return ServiceResponse::success('Contact Details fetched successfully.', $contactDetails);

        }
        return view('Pages.ContactDetail.Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contactTypes = collect(ContactType::cases())
        ->mapWithKeys(fn($case) => [$case->name => $case->value])
        ->toArray();

        return view('Pages.ContactDetail.create', ['contactTypes' => $contactTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(createContactDetailRequest $request)
    {
        $data = $request->validated();
        $action = $this->contactDetailService->store($data);
        $this->toasterService->toast($action);
        return redirect()->route('contact-details.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactDetail $contactDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactDetail $contactDetail)
    {
        return view('Pages.ContactDetail.edit', ['contactDetail' => $contactDetail]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactDetailRequest $request, ContactDetail $contactDetail)
    {
        $data = $request->validated();
        $action = $this->contactDetailService->update($data, $contactDetail);
        $this->toasterService->toast($action);
        return redirect()->route('contact-details.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactDetail $contactDetail)
    {
        $contactDetail->delete();
        toastr()->success('Contact detail deleted successfully');
        return redirect()->route('contact-details.index');

    }
}

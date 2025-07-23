<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactDetail;
use Illuminate\Http\Request;
use App\Constants\ContactDetailConstants;
use App\Enums\Status;
use Yajra\DataTables\DataTables;
use App\Enums\ContactType;
use App\Http\Requests\Admin\ContactDetail\createContactDetailRequest;
use App\Services\ContactDetailService;
use App\Services\ToasterService;
use App\Http\Requests\Admin\ContactDetail\UpdateContactDetailRequest;
use App\Exceptions\Handler;

class ContactDetailController extends Controller
{

    public function __construct(private ContactDetailService $contactDetailService, private ToasterService $toasterService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                // dd($request);
                $query = ContactDetail::query();

                if ($request->filled('status')) {
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
                        if ($row->contact_type == ContactType::EMAIL) {
                            return 'Email';
                        } else {
                            return 'Phone';
                        }
                    })
                    ->rawColumns(['actions'])
                    ->make(true);

                return $contactDetails;

                // return ServiceResponse::success('Contact Details fetched successfully.', $contactDetails);

            }
            return view('Pages.ContactDetail.Index');
        } catch (\Throwable $e) {
            $message = 'Error while fetching Contact Details';
            Handler::logError($e, $message);
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
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
        try {
            $data = $request->validated();
            $action = $this->contactDetailService->store($data);
            $this->toasterService->toast($action);
            return redirect()->route('contact-details.index');
        } catch (\Throwable $e) {
            $message = 'Error while storing Contact Detail';
            Handler::logError($e, $message);
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
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
        try {
            $data = $request->validated();
            $action = $this->contactDetailService->update($data, $contactDetail);
            $this->toasterService->toast($action);
            return redirect()->route('contact-details.index');
        } catch (\Throwable $e) {
            $message = 'Error while updating Contact Detail';
            Handler::logError($e, $message);
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactDetail $contactDetail)
    {
        try {
            $contactDetail->delete();
            toastr()->success('Contact detail deleted successfully');
            return redirect()->route('contact-details.index');
        } catch (\Throwable $e) {
            $message = 'Error while deleting Contact Detail';
            Handler::logError($e, $message);
            $this->toasterService->exceptionToast($message);
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Enums\ContactBoardStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ContactSendMessageRequest;
use App\Http\Requests\ContactStatusUpdateRequest;
use App\Http\Requests\FollowUpRequest;
use App\Jobs\SendEmailJob;
use App\Mail\InviteContactEmail;
use App\Models\Board;
use App\Models\BoardContact;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\FollowUp;
use App\Models\Label;
use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MetaTag;
use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Shuchkin\SimpleXLSX;
use Illuminate\Support\Facades\Storage;
use App\Classes\Helper\OpenAi;
use App\Models\User;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        MetaTag::set('title', config('app.rankup.company_title').' - Contacts');
        MetaTag::set('description', config('app.rankup.company_title').' Contacts Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
    }

    /**
     * Display a Board of user
     *
     * @param Request $request
     *
     * @return Response
     */
    public function board(Request $request)
    {
        $user = Auth::user();
        if (!$user->board) {
            Board::create([
                'user_id' => $user->id,
                'is_current' => 1,
            ]);
            Auth::user()->refresh();
        }
        $statusRange = ContactBoardStatus::asSelectArray();
        $board = $user->board;

        $statusRangeKeys = json_encode(array_keys($statusRange));

        return view('seller.contacts.index', compact('statusRange', 'board', 'statusRangeKeys'));
    }

    /**
     * Search user in the board
     *
     * @param Request $request
     *
     * @return Response
     */
    public function boardFilter(Request $request) 
    {
        if(!empty($request->sorting_data)) {
            $orderType = $request->sorting_data;
            $user = Auth::user();
            if (!$user->board) {
                Board::create([
                    'user_id' => $user->id,
                    'is_current' => 1,
                ]);
                Auth::user()->refresh();
            }
            $statusRange = ContactBoardStatus::asSelectArray();
            $board = $user->board;
            $contacts = Contact::where(['contacts.user_id' => $user->id])->whereNotIn('contacts.id', function ($query) use ($board) {
                $query->select('board_contact.contact_id')->from('board_contact')->where(['board_contact.board_id' => $board->id])->whereNull('board_contact.deleted_at')->groupBy('board_contact.contact_id');
            });

            $board_contacts = [];
            $boardContacts = $board->withOutOrderContacts();
            if (!is_null($orderType)) {
                if($orderType == "creation-date-asc"){
                    $contacts->orderBy( 'contacts.created_at', 'asc' );
                    $boardContacts->orderBy('created_at', 'asc');
                } else if($orderType == "creation-date-desc") {
                    $contacts->orderBy( 'contacts.created_at', 'desc' );
                    $boardContacts->orderBy('created_at', 'desc');
                } else if($orderType == "followup-date-asc") {
                    $contacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'asc')->select('follow_ups.follow_up_date','contacts.*');
                    $boardContacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'asc');
                } else if($orderType == "followup-date-desc") {
                    $contacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'desc')->select('follow_ups.follow_up_date','contacts.*');
                    $boardContacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'desc');
                } else {
                    $contacts->orderBy( 'contacts.name', $orderType );
                    $boardContacts = $boardContacts->orderBy('name', $orderType);
                }
            }

            $contacts = $contacts->get();
            $boardContacts = $boardContacts->get();

            foreach ($boardContacts as $board_contact) {
                $board_contacts[$board_contact->pivot->status][] = $board_contact;
            }

            $view = view('seller.contacts.contact_data', compact('contacts', 'board_contacts', 'statusRange', 'board'))->render();

            return response()->json([
                'view' => $view,
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactRequest $request
     *
     * @return JsonResponse
     */
    public function store(ContactRequest $request)
    {
        $data = $request->all();

        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        if ($request->hasFile('contact_image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('contact_image'), 'contacts');
            $data['profile_image'] = $imageName;
        }

        $data['order'] = 1;
        $contact = Contact::create($data);

        if ($request->sort_array) {
            $request->sort_array = json_decode($request->sort_array, true);
            Contact::whereIn('id', $request->sort_array)->increment('order'); // Increment order by plus one
        }

        ContactLog::createLog($contact->id, 0);

        if (isset($data['follow_up_date'])) {
            $followUp = FollowUp::updateOrCreate(
                ['contact_id' => $contact->id, 'user_id' => Auth::id()],
                ['follow_up_date' => convertDateFormatWithTimezone($data['follow_up_date'] . ' 00:00:00', 'd/m/Y H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM')]
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'user_pic' => isset($contact->profile_image) ? CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png'),
                'follow_up_date' => !empty($followUp) && isset($followUp->follow_up_date) ? convertDateFormatWithTimezone($followUp->follow_up_date, 'Y-m-d H:i:s', 'd/m/Y', 'CRM-TO-FRONT') : null,
                'created_at' => date('d/m/Y', strtotime($contact->created_at)),
                'follow_up_count' => !empty($followUp) && isset($followUp->follow_up_date) ? $followUp->getDayCount() : "",
            ],
        ], 200);
    }

    /**
     * Update Status.
     *
     * @param ContactStatusUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function updateStatus(ContactStatusUpdateRequest $request)
    {
        if ($request->sort_array) {
            $counter = 1;
            foreach ($request->sort_array as $contactId) {
                Contact::where(['id' => $contactId])->update(['order' => $counter]);
                $counter++;
            }
        }
        if ($request->update_status == 0) {
            BoardContact::where([
                'board_id' => $request->board_id,
                'contact_id' => $request->id,
            ])->delete();
            ContactLog::where('contact_id', $request->id)->where('status', '>', $request->update_status)->delete();
            return response()->json([
                'success' => true,
            ], 200);
        }
        BoardContact::updateOrCreate([
            'board_id' => $request->board_id,
            'contact_id' => $request->id,
        ], [
            'status' => $request->update_status,
        ]);
        $checkOldStatus = ContactLog::where('contact_id', $request->id)->first();
        
        if(isset($checkOldStatus) && !empty($checkOldStatus)) {
            $oldStatus = $checkOldStatus->status;
        } else {
            $contactLog = ContactLog::createLog($request->id, $request->update_status);
            $oldStatus = $contactLog->status;
        }
        $newStatus = (int) $request->update_status;
        $difference = $newStatus - $oldStatus;
	    $isPresent = (int) $request->is_present;

        if ($difference >= 0) {
            $status = $oldStatus;
            do { 
                if ($status >= 10 || $status >= $newStatus) {
                    ContactLog::createLog($request->id, ContactBoardStatus::MESSAGE_SENT);
                	break;
                } else if($newStatus == ContactBoardStatus::NOT_INTERESTED) {
                    ContactLog::createLog($request->id, ContactBoardStatus::MESSAGE_SENT);
                    ContactLog::createLog($request->id, ContactBoardStatus::NOT_INTERESTED);
                    break;
                }
                $difference -= 1;
                $status += 1;
                $skip = false;
                if ($status == 6 && $newStatus == 7 && $oldStatus != 6) {
                    $skip = true;
                } else if (in_array($status, [6, 7]) && in_array($newStatus, [8, 9]) && !in_array($oldStatus, [6, 7])) {
                    $skip = true;
                } else if (in_array($status, [6, 7, 8]) && $newStatus == 9 && !in_array($oldStatus, [6, 7, 8])) {
                    $skip = true;
                }
                if (!$skip) {
                    ContactLog::createLog($request->id, $status);
                }
            } while ($difference != 0);
        } else {
            ContactLog::where('contact_id', $request->id)->where('status', '>', $request->update_status)->delete();
        }
        
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Update created resource in storage.
     *
     * @param ContactRequest $request
     *
     * @return JsonResponse
     */
    public function update(ContactRequest $request)
    {
        $contact = Contact::find($request->id);
        $data = request()->all();

        if(!empty($contact)) {
            $followUp = [];
            if (isset($data['follow_up_date']) && !is_null($request->follow_up_date)) {
                $followUp = FollowUp::updateOrCreate(
                    ['contact_id' => $contact->id, 'user_id' => Auth::id()],
                    ['follow_up_date' => convertDateFormatWithTimezone($data['follow_up_date'] . ' 00:00:00', 'd/m/Y H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM')]
                );
            } else {
                FollowUp::where(['contact_id' => $contact->id, 'user_id' => Auth::id()])->delete();
            }

            if ($request->hasFile('contact_image')) {
                if (isset($contact->profile_image)) {
                    CommonUtil::removeFile($contact->profile_image);
                }
                $imageName = CommonUtil::uploadFileToFolder($request->file('contact_image'), 'contacts');
                $data['profile_image'] = $imageName;
            } else {
                $data['profile_image'] = $contact->profile_image;
            }
            $contact->update($data);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'user_pic' => isset($contact->profile_image) ? CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png'),
                    'follow_up_date' => !empty($followUp) && isset($followUp->follow_up_date) ? convertDateFormatWithTimezone($followUp->follow_up_date, 'Y-m-d H:i:s', 'd/m/Y', 'CRM-TO-FRONT') : null,
                    'created_at' => date('d/m/Y', strtotime($contact->created_at)),
                    'follow_up_count' => !empty($followUp) && isset($followUp->follow_up_date) ? $followUp->getDayCount() : "",
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
            ], 200);    
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        $followUp = FollowUp::select('follow_up_date')->where(['contact_id' => $id])->first();
        if ($contact) {
            $survey = null;
            foreach ($contact->survey as $survey) {
                $survey = Survey::where(['id' => $survey->event->survey_id])->first();
            }
	        $label = __('Labels');
	        $color = '#56B2FF';
	        $labelData = $contact->labels()->get();

            $labelsData = [];

            if(isset($labelData) && !empty($labelData)) {
                foreach($labelData as $key => $label) {
                    $labelData[$key]['label'] =  $label->name;
                    $labelData[$key]['color'] = $label->color;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'phone' => $contact->phone,
                    'contacted_through' => $contact->contacted_through,
                    'message' => $contact->message,
                    'user_pic' => isset($contact->profile_image) ? CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png'),
                    'follow_up_date' => !empty($followUp) && isset($followUp->follow_up_date) ? convertDateFormatWithTimezone($followUp->follow_up_date, 'Y-m-d H:i:s', 'd/m/Y', 'CRM-TO-FRONT') : null,
                    'link' => $contact->link,
                    'created_at' => date('d/m/Y', strtotime($contact->created_at)),
	                'label' => $labelData
                ],
                'survey' => view('seller.contacts._survey', compact('survey', 'contact'))->render(),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            if ($contact->followUp()) {
                $contact->followUp()->delete();
            }
            if ($contact->log()) {
                $contact->log()->delete();
            }
            if ($contact->contactEvent()) {
                $contact->contactEvent()->delete();
            }
            if ($contact->contactBoard()) {
                $contact->contactBoard()->delete();
            }

            if($contact->labels()) {
	            $contact->labels()->detach();
            }

            $contact->delete();
            if (isset($contact->profile_image)) {
                CommonUtil::removeFile($contact->profile_image);
            }
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Send contact message.
     *
     * @param ContactSendMessageRequest $request
     *
     * @return JsonResponse
     */
    public function sendMessage(ContactSendMessageRequest $request)
    {
        // Email send here.
        $contact = Contact::find($request->id);
        $contact->message = $request->message;
        $contact->save();

        if ($contact->email) {
            $email = new InviteContactEmail($contact);
            dispatch(new SendEmailJob($email));
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Create Or Update FollowUp
     *
     * @param FollowUpRequest $request
     *
     * @return JsonResponse
     */
    public function followUp(FollowUpRequest $request)
    {
        $followUp = FollowUp::updateOrCreate(
            ['contact_id' => $request->contact_id, 'user_id' => Auth::id()],
            ['follow_up_date' => convertDateFormatWithTimezone($request->follow_up_date . ' 00:00:00', 'd/m/Y H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM')]
        );

        $contact = Contact::find($request->contact_id);
        $contact->message = $request->reason;
        $contact->save();

        return response()->json([
            'success' => true,
            'follow_up_count' => $followUp->getDayCount(),
            'contact_id' => $request->contact_id,
        ], 200);
    }

    /**
     * Ger followUp date by contact id
     *
     * @param int $contact_id
     *
     * @return JsonResponse
     */
    public function getFollowUpDate($contact_id)
    {
        $followUp = FollowUp::select('follow_up_date')->where(['contact_id' => $contact_id])->first();
        $contact = Contact::find($contact_id);
        if ($followUp) {
            $followUpDate = convertDateFormatWithTimezone($followUp->follow_up_date, 'Y-m-d H:i:s', 'd/m/Y', 'CRM-TO-FRONT');
            return response()->json([
                'success' => true,
                'date' => $followUpDate,
                'reason' => $contact->message,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
            ], 200);
        }
    }

	/**
	 * Task Labels Update.
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function contactLabelsUpdate($id, Request $request) 
    {
		$data = $request->all();
		$res = [];
		$contact = Contact::find($id);

        if(!empty($contact)) {
            if(isset($data['labels']) && !empty($data['labels']) && $data['labels'][0] !== 'none'){
                $contact->labels()->sync($data['labels']);
            } else {
                $contact->labels()->sync([]);
            }

            $labels = $contact->labels()->get();
            return response()->json([
                'success' => true,
                'data' => ['id' => $id],
                'html' => view('seller.contacts.contact_card_modal_label', compact('labels'))->render()
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => ['id' => $id],
            'html' => ''
        ], 200);	
	}

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getBoardStatusData(Request $request, $id)
    {
        $search = $request->search;
        $user = Auth::user();
        $board = $user->board;
        $contacts = [];
        if(!empty($request->sorting_data)) {
            $orderType = $request->sorting_data;
            $boardContacts = $board->withOutOrderContacts();
            if (!is_null($orderType)) {
                if($orderType == "creation-date-asc"){
                    $boardContacts->orderBy('created_at', 'asc');
                } else if($orderType == "creation-date-desc") {
                    $boardContacts->orderBy('created_at', 'desc');
                } else if($orderType == "followup-date-asc") {
                    $boardContacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'asc');
                } else if($orderType == "followup-date-desc") {
                    $boardContacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'desc');
                } else {
                    $boardContacts = $boardContacts->orderBy('name', $orderType);
                }
            }
            if (!is_null($request->search)) {
                $boardContacts->where(function ($query) use($search) {
                    $query->where('name', 'like', '%' . $search . '%')->orWhere('message', 'like', '%' . $search . '%')->orWhereHas('labels', function ($mQ) use ($search){
                        $mQ->where('labels.name', 'like', '%' . $search . '%');
                    });
                });
            }
            $boardContacts = $boardContacts->get();
        } else if (!is_null($request->search)) {
            $boardContacts = $board->contacts()
                ->where(function ($query) use($search) {
                    $query->where('name', 'like', '%' . $search . '%')->orWhere('message', 'like', '%' . $search . '%')->orWhereHas('labels', function ($mQ) use ($search){
                        $mQ->where('labels.name', 'like', '%' . $search . '%');
                    });
                })
                ->get();
        } else {
            $boardContacts = $board->contacts()->get();
        }

        foreach ($boardContacts as $board_contact) {
            if($board_contact->pivot->status == $id) {
                $contacts[] = $board_contact;
            }
        }

        return response()->json([
            'success' => true,
            'html' => view('seller.contacts.contact_column_data', compact('contacts', 'id', 'board'))->render()
        ], 200);
    }

    /**
     * Get contact board first column data
     *
     * @param Request $request

     * @return JsonResponse
     */
    public function getContactBoardData(Request $request)
    {
        $search = $request->search;
        $user = Auth::user();
        $board = $user->board;
        if(!empty($request->sorting_data)) {
            $orderType = $request->sorting_data;
            $contacts = Contact::where(['contacts.user_id' => $user->id])->whereNotIn('contacts.id', function ($query) use ($board) {
                $query->select('board_contact.contact_id')->from('board_contact')->where(['board_contact.board_id' => $board->id])->whereNull('board_contact.deleted_at')->groupBy('board_contact.contact_id');
            });
            if (!is_null($orderType)) {
                if ( $orderType == "creation-date-asc" ) {
                    $contacts->orderBy( 'contacts.created_at', 'asc' );
                } else if ( $orderType == "creation-date-desc" ) {
                    $contacts->orderBy( 'contacts.created_at', 'desc' );
                } else if ( $orderType == "followup-date-asc" ) {
                    $contacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'asc')->select('follow_ups.follow_up_date','contacts.*');
                } else if ( $orderType == "followup-date-desc" ) {
                    $contacts->join('follow_ups', 'follow_ups.contact_id', '=', 'contacts.id')->orderBy('follow_ups.follow_up_date', 'desc')->select('follow_ups.follow_up_date','contacts.*');
                } else {
                    $contacts->orderBy( 'contacts.name', $orderType );
                }
            }
            if (!is_null($request->search)) {
                $contacts->when(!is_null($search), function ($q) use ($search) {
                    return $q->where(function ($query) use ($search) {
                        return $query->where('name', 'like', '%' . $search . '%')->orWhere('message', 'like', '%' . $search . '%') ->orWhereHas('labels', function ($mQ) use ($search){
                            $mQ->where('labels.name', 'like', '%' . $search . '%');
                        });
                    });
                });
            }
            $contacts = $contacts->get();
        } else {
            $contacts = Contact::where(['user_id' => $user->id])
               ->whereNotIn('id', function ($query) use ($board) {
                   $query->select('contact_id')->from('board_contact')->where(['board_id' => $board->id])->whereNull('deleted_at')->groupBy('contact_id');
               })
               ->when(!is_null($search), function ($q) use ($search) {
                   return $q->where(function ($query) use ($search) {
                       return $query->where('name', 'like', '%' . $search . '%')->orWhere('message', 'like', '%' . $search . '%') ->orWhereHas('labels', function ($mQ) use ($search){
                            $mQ->where('labels.name', 'like', '%' . $search . '%');
                       });
                   });
               })
               ->orderBy('order', 'asc')
               ->get();
        }

        return response()->json([
            'success' => true,
            'html' => view('seller.contacts.contact_first_column_data', compact('contacts', 'board'))->render()
        ], 200);
    }

    /**
     * Upload contact with xsl file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadContacts(Request $request) 
    {
        $this->validator($request->all())->validate();
        try {
            $errors_arr = [];
            $upload_error = false;
            $path = Storage::putFileAs('public/contact-csv-upload', $request->file('file'),rand(0,999999)."_".$request->file('file')->getClientOriginalName());
            $import = new ContactsImport;
            $import->import($path);

         }
         catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors_arr = [];
            $i=0;
            foreach($failures as $failure) {
                if($i == 0) {
                    $errors_arr[$failure->row() -1][] = __('There was an error on row').' '.($failure->row() - 1).' '.$failure->errors()[0];
                }
                $i=1;
            }
            return response()->json([
                'errors' => array_values($errors_arr),
                'upload_error' => true,
            ], 422);
         }
         
        return response()->json([
            'success' => true,
        ], 200);

    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = array(
            'file.required'     => __('File is required'),
            'file.mimes'        => __('File must be a file of type:xlsx,xls,ods'),
            'file.max'          => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
        );

        return Validator::make($data, [
            'file' => ['required','mimes:xlsx,xls,ods','max:'.env('IMAGE_UPLOAD_SIZE')],
        ], $message);
    }

    /**
     * Upload contact with xsl file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readContactsUploadData(Request $request) 
    {
        if ( $xlsx = SimpleXLSX::parse($request->file('file')) ) {
            if(isset($request->update_status)) {
                $updateStatus = $request->update_status;
            } else {
                $updateStatus = 0;
            }
            $header_values = $rows = [];
            foreach ( $xlsx->rows() as $k => $r ) {
                if ( $k === 0 ) {
                    $header_values = $r;
                    continue;
                }
                $rows[] = array_combine( $header_values, $r);
            }
            return response()->json([
                'success' => true,
                'data' => $rows,
                'update_status' => $updateStatus,
                'html' => view('seller.common._contact_upload_preview', compact('rows','updateStatus'))->render()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'errors' => __('Something went wrong')
            ], 200);
        }
    }

    /**
     * Generate ai message
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateAiMessage(Request $request)
    {
        $inputText = $request->input('prompt');
        $openAiData = OpenAi::generateMessage($inputText . ' language');

        if(isset($openAiData['success']) && $openAiData['success'] == true && isset($openAiData['ai_message'])) {
            $aiMessage = $openAiData['ai_message'];
            $view =  view('seller.contacts.ai_writing_message', compact('aiMessage'))->render();

            return response()->json([
                'success' => true,
                'html' => $view
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        } 
    }

    /**
     * Get ai models
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAiModels(Request $request)
    {
        $contact = Contact::find($request->contact_id);
        $contactName = $contactMessage = '';

        if (!empty($contact)) {
            $contactName = $contact->name ?? '';
            $contactMessage = $contact->message ?? '';
        }

        $languageMap = [
            'fr' => 'french',
            'es' => 'spanish',
            'cs' => 'Czech language',
        ];

        $currentLocale = app()->getLocale();
        $language = $languageMap[$currentLocale] ?? 'english';

        $user = Auth::user();
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id, $user->id);
        $memberIds = array_unique(array_merge($memberIds, User::getUplineArray($user)));
        $teamsize = count($memberIds);
        $currentUser = $user->name;

        $modelsData = [
            [
                'value' => "Write a 5 line message for someone named $contactName that will invite them to your event hosted on RankUp. If not blank, personalize the message based on the user notes: $contactMessage in $language",
                'model' => 'Invite to your RankUp event',
            ],
            [
                'value' => "Write a 4 line message inviting $contactName to your team of $teamsize in order to learn to generate revenues and become financially independent in $language",
                'model' => 'Invite to your team',
            ],
            [
                'value' => "Generate a 5 line text about your 'why' based on the following: $currentUser in $language",
                'model' => 'Share your why',
            ],
        ];

        $view = view('seller.contacts.ai_writing_models', compact('modelsData'))->render();

        return response()->json([
            'success' => true,
            'html' => $view,
        ], 200);
    }
}
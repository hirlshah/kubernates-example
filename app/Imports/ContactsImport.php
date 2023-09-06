<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\FollowUp;
use App\Models\BoardContact;
use App\Enums\ContactBoardStatus;
use App\Models\ContactLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ContactsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable, RegistersEventListeners;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $contact = Contact::create([
                'user_id' => Auth::id(),
                'event_id' => null, 
                'name' => $row["name"],
                'email' => $row["email"],
                'phone' => $row["phone"],
                'order' => 1,
            ]);

            if(request('update_status') > 0) {

                if (request('sort_array')) {
                    $sort_array = json_decode(request('sort_array'), true);
                    Contact::whereIn('id', $sort_array)->increment('order'); // Increment order by plus one
                }
    
                ContactLog::createLog($contact->id, 0);
    
                if (!empty(request('update_status')) == 8) {
                    $followUp = FollowUp::updateOrCreate(
                        ['contact_id' => $contact->id, 'user_id' => Auth::id()],
                        ['follow_up_date' => date("Y-m-d h:i:s",strtotime($row["follow_up_date"]))]
                    );
    
                    $contactUpdateFollowUp = Contact::find($contact->id);
                    $contactUpdateFollowUp->message = $row["reason"];
                    $contactUpdateFollowUp->save();
                }
    
                if (!empty(request('update_status')) == 0) {
                    BoardContact::where([
                        'board_id' => request('board_id'),
                        'contact_id' => $contact->id,
                    ])->delete();
                }
    
                BoardContact::updateOrCreate([
                    'board_id' => request('board_id'),
                    'contact_id' => $contact->id,
                ], [
                    'status' => request('update_status'),
                ]);
        
    
                $checkOldStatus = ContactLog::where('contact_id', $contact->id)->first();
            
                if(isset($checkOldStatus) && !empty($checkOldStatus)) {
                    $oldStatus = $checkOldStatus->status;
                } else {
                    $contactLog = ContactLog::createLog($contact->id,request('update_status'));
                    $oldStatus = $contactLog->status;
                }
                $newStatus = (int) request('update_status');
                $difference = $newStatus - $oldStatus;
                $isPresent = (int) request('is_present');
    
                if ($difference >= 0) {
                    $status = $oldStatus;
                    do {
                        if ($status >= 10 || $status >= $newStatus) {
                            ContactLog::createLog($contact->id, ContactBoardStatus::MESSAGE_SENT);
                            break;
                        } else if($newStatus == ContactBoardStatus::NOT_INTERESTED) {
                            ContactLog::createLog($contact->id, ContactBoardStatus::MESSAGE_SENT);
                            ContactLog::createLog($contact->id, ContactBoardStatus::NOT_INTERESTED);
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
                            ContactLog::createLog($contact->id, $status);
                        }
                    } while ($difference != 0);
                } else {
                    ContactLog::where('contact_id', $contact->id)->where('status', '>', request('update_status'))->delete();
                }  
            }          
        }

    }

    public function rules(): array
    {
        $rule = [
            'name' => 'required|max:191',
            'email' => 'required|email',
            'contact_image' => 'max:'.env('IMAGE_UPLOAD_SIZE').'|mimes:jpg,jpeg,png',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'link' => 'max:191',
        ];
        if(request("update_status") == 8) {
            $rule['follow_up_date'] = 'required';
        } else if(request("update_status") == 9) {
            $rule['not_interested'] = 'required';
        }
        
        return $rule;
       
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => __('Full Name is required'),
            'name.max' => __('Full name not be greater than 191 characters.'),
            'email.required' => __('Email is required'),
            'email.email' => __('Enter valid email'),
            'contact_image.mimes' => __('Image type must be a type of jpg,jpeg,png'),
            'contact_image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
            'phone.min' => __('Phone number must be at least 10 digits'),
            'phone.regex' => __('Phone number format is invalid'),
            'link.max' => __('The link must not be greater than 191 characters.'),
            'follow_up_date.required' => __('Date is required'),
            'not_interested.required' => __('Not interested filed is required'),
        ];
    }
}

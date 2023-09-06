<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mail;

class ContactUsFormController extends Controller
{
    /**
     * Contact Submit
     *
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function ContactUsForm(Request $request) 
    {
        $message = array(
            'name.required' => __('Name is required'),
            'email.required' => __('Email is required'),
            'phone.required' => __('Phone is required'),
            'message.required' => __('Message is required')
        );
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'message' => 'required'
        ],$message);

        ContactUs::create($request->all());

        //  Send mail to admin
        // $mail = \Mail::send('frontend.mail', array(
        // 	'name' => $request->get('name'),
        // 	'email' => $request->get('email'),
        // 	'phone' => $request->get('phone'),
        // 	'subject' => 'Contact Us',
        // 	'user_query' => $request->get('message'),
        // ), function($message) use ($request){
        // 	$message->from($request->email);
        // 	$message->to('testdev@yopmail.com', 'Admin')->subject('Contact Us');
        // });

        return json_encode(['success' => true, 'message' => __('We have received your message and would like to thank you for writing to us.')]);
    }
}

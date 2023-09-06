<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Help;
use MetaTag;

class HelpController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Helps'));
        MetaTag::set('description', config('app.rankup.company_title').' Aide');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));

        $helps = Help::get();
        
        return view('seller.helps.index',compact('helps'));
    }
}
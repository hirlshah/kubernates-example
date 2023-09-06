<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CommonController extends Controller
{
    public function getPeopleList(Request $request) 
    {
        $users = User::query();
        if(!empty($request->search_text)) {
            $searchKeyword = $request->search_text;
            $users->where('name', 'LIKE', "%{$searchKeyword}%")->orWhere('last_name', 'LIKE', "%{$searchKeyword}%")->orWhere('user_name', 'LIKE', "%{$searchKeyword}%")->orWhere('email', 'LIKE', "%{$searchKeyword}%");
        }
        $users = $users->paginate(10);
        if(!empty($users)) {
            $view = view('seller.modal.components.user-list', compact('users'))->render();
             return response()->json([
                'success' => true,
                'html' => $view,
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        }   
    }
}

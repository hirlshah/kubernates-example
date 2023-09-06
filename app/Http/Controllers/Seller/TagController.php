<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param TagRequest $request
     *
     * @return jsonResponse
     */
    public function store(TagRequest $request) 
    {
        $tag = Tag::create([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Get tag list
     */
    public function getList() 
    {
        return Tag::getOptions();
    }
}

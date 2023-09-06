<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Models\Contact;
use App\Models\Label;
use App\Models\TaskLabel;
use App\Models\TrelloTask;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Labelled;

class LabelController extends Controller
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
	 * @param int $id
	 * @param string $type
	 *
	 * @return Application|Factory|View|JsonResponse
	 */
	public function index($id, $type) 
	{
		$modalLabels = [];
		$user = Auth::user();
		if($type == 'contact') {
			$modalData = Contact::find($id);
		}
		if(isset($modalData)){
			$modalLabels = $modalData->labels()->pluck('labelleds.label_id', 'labelleds.label_id')->toArray();
		}
		$labels = Label::where('user_id', $user->id)->get();
		return view('seller.common._label_list', compact('labels', 'modalLabels'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param LabelRequest $request
	 *
	 * @return jsonResponse
	 */
	public function store(LabelRequest $request) 
	{
		$data = $request->all();
		$data['user_id'] = Auth::id();
		$label = Label::create($data);
		$labels = Label::pluck('name', 'id');
		return response()->json([
			'success' => true,
			'data' => $labels,
			'label' => $label
		], 200);
	}

	/**
	 * Get data by id
	 *
	 * @param int $id
	 *
	 * @return jsonResponse
	 */
	public function show($id) 
	{
		$label = Label::find($id);
		return response()->json([
			'success' => true,
			'data' => $label
		], 200);
	}

	/**
	 * Update label
	 *
	 * @param int $id
	 * @param LabelRequest $request
	 *
	 * @return jsonResponse
	 */
	public function update($id, LabelRequest $request) 
	{
		$label = Label::find($id);
		$data = $request->all();
		$label->update($data);
		return response()->json([
			'success' => true,
			'data' => $label
		], 200);
	}

	/**
	 * Delete label
	 *
	 * @param int $id
	 *
	 * @return jsonResponse
	 */
	public function destroy($id) 
	{
		$label = Label::find($id);

		if(!empty($label)) {
			Labelled::where('label_id', $label->id)->delete();
			$labelId = $label->id;
			$label->delete();

			return response()->json([
				'success' => true,
				'label_id' => $labelId
			], 200);
		} else {
			return response()->json([
				'success' => false
			], 200);
		}
	}
}
<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\TrelloStatus;
use App\Models\TrelloTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TrelloBoardRequest;
use App\Http\Requests\TrelloStatusRequest;
use App\Http\Requests\TrelloBoardCategoryRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\TrelloBoard;
use App\Models\TrelloBoardLog;
use App\Models\User;
use App\Enums\TrelloType;
use App\Models\TrelloBoardCategory;
use App\Models\TrelloTaskComment;
use App\Classes\Helper\CommonUtil;
use App\Models\TrelloTaskAttachment;
use App\Enums\AttachmentTypes;
use App\Models\TourLogs;
use App\Models\TrelloTaskCommentAttachment;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\TrelloTaskCommentResource;
use App\Models\ModuleConfig;
use Carbon\Carbon;
use MetaTag;
use DB;

class TrelloController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() 
	{
		$this->middleware(['auth', 'verified']);

		$recordExist = ModuleConfig::checkForModuleNotExist('Board');

        if($recordExist) {
            abort(404);
        }
	}

	/**
	 * Trello board listing
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request) 
	{
		MetaTag::set('title', config('app.rankup.company_title')." - ".__('Task Board'));
		MetaTag::set('description', config('app.rankup.company_title').' Task Board Page');
		MetaTag::set('image', asset(config('app.rankup.company_logo_path')));

		$trelloBoardsQuery = TrelloBoard::whereHas('users', function ($query) {
			    $query->where('user_id', Auth::User()->id);
			});

		if ($request->has('search_text')) {
            $searchText = $request->input('search_text');
            $trelloBoardsQuery->where('title', 'LIKE', "%$searchText%");
        }

        if (isset($request->sorting_type) && $request->sorting_type != 'most_recents') {
        	$orderType = $request->sorting_type;
        	if ($orderType == "creation-date-asc") {
	            $trelloBoardsQuery->orderBy('created_at', 'ASC');
            }
        } else {
        	$trelloBoardsQuery->orderBy('created_at', 'DESC');
        }

        $trelloBoards = $trelloBoardsQuery->get();
        $trelloBoardCount = $trelloBoardsQuery->count();
        $params = compact('trelloBoards', 'trelloBoardCount') ;

        if($request->ajax()) {
        	return response()->json([
	            'success' => true,
	            'html' => view('seller.trello_board.trello_data', $params)->render(),
	            'trello_board_count' => __('You currently have') .' '. $trelloBoardCount .' '. __('boards')
	        ], 200);
        }
		
		return view('seller.trello_board.index', $params);
	}

	/**
	 * Display trello board
	 *
	 * @param string $id
	 *
	 * @return Response
	 */
	public function detail(string $id) 
	{
		MetaTag::set('title', env('COMPANY_TITLE')." - ".__('Task Board'));
		MetaTag::set('description', env('COMPANY_TITLE').' Task Board Page');
		MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
		
		$trelloBoards = TrelloBoard::whereHas('users', function ($query) {
			    $query->where('user_id', Auth::User()->id);
			})->pluck('title', 'id')->toArray();

		$trelloBoard = TrelloBoard::find((int) getDecrypted($id));

		if(!empty($trelloBoard)) {
			if (!in_array($trelloBoard->id, $trelloBoards)) {
			    $trelloBoard->users()->syncWithoutDetaching(Auth::User()->id);
			}

			$tourLogs = TourLogs::updateOrCreate(['user_id' => Auth::User()->id],[
				'user_id' => Auth::User()->id,
				'tour_type' => TrelloType::TRELLO,
			]);

			$userTrelloModal = false;
			if ($tourLogs->wasRecentlyCreated) {
				$userTrelloModal = true;
			}
			$trelloBoardPeopleCount = $trelloBoard->users->count();

			$taskStatuses = TrelloStatus::where('trello_board_id', $trelloBoard->id)->orderBy('order', 'asc')->get();
			
			$users = User::get();
		
			$taskStatusIds = TrelloStatus::where('trello_board_id', $trelloBoard->id)->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

			$taskStatusIds = json_encode(array_keys($taskStatusIds));

			$board_id = (int) getDecrypted($id);

			$params = compact('taskStatuses', 'trelloBoards', 'trelloBoard', 'users', 'trelloBoardPeopleCount', 'userTrelloModal', 'taskStatusIds', 'board_id') ;
			
			return view('seller.trello_board.detail', $params);
		}
		return redirect()->back();
	}

	/**
	 * Get status wise task data
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function getStatusColumnData($id) 
	{
		$statusId = $id;
		$trelloTasks = TrelloTask::where('trello_status_id', $id)->get();
		return response()->json([
            'success' => true,
            'html' => view('seller.trello_board.task_data', compact('trelloTasks', 'statusId'))->render()
        ], 200);
	}

	/**
	 * Add trello board
	 *
	 * @param TrelloBoardRequest $request
	 *
	 * @return JsonResponse
	 */
	public function addTrelloBoard(TrelloBoardRequest $request) 
	{
		$data = $request->all();
		$data['user_id'] = Auth::User()->id;
		$trelloBoard = TrelloBoard::create($data);
		if(isset($trelloBoard)) {
			$trelloBoard->users()->syncWithoutDetaching(Auth::User()->id);
			return response()->json([
				'success' => true,
				'redirect_url' => route('seller-task-board', getEncrypted($trelloBoard->id))
			], 200);
		} else {
			return response()->json([
				'success' => false,
			], 200);
		}
	}

	/**
	 * Update trello board
	 *
	 * @param TrelloBoardRequest $request
	 *
	 * @return JsonResponse
	 */
	public function updateTrelloBoard(TrelloBoardRequest $request) 
	{
		$data = $request->all();
		$trelloBoard = TrelloBoard::find($request->trello_board_id);
		
		$trelloBoard->update($data);
		
		return response()->json([
			'success' => true,
			'redirect_url' => route('seller-task-board', getEncrypted($trelloBoard->id))
		], 200);	
	}

	/**
	 * Add trello board category
	 *
	 * @param TrelloBoardCategoryRequest $request
	 *
	 * @return JsonResponse
	 */
	public function addTrelloBoardCategory(TrelloBoardCategoryRequest $request) 
	{
		$data = $request->all();
		$data['user_id'] = Auth::User()->id;
		$trelloBoard = TrelloBoardCategory::create($data);
		if(isset($trelloBoard)) {
			return response()->json([
				'success' => true,
			], 200);
		} else {
			return response()->json([
				'success' => false,
			], 200);
		}
	}

	/**
	 * Add people in trello board
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function addPeopleToTrelloBoard(Request $request) 
	{
		$trelloBoard = TrelloBoard::find($request->trello_board_id);
		$peoples = [];
		if(isset($request->peoples)) {
			$peoples = explode(',', $request->peoples);
		}
		$trelloBoard->users()->sync($peoples);
		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function addTrelloTask(Request $request) 
	{
		$data = $request->all();

		if (!isset($data['user_id'])) {
			$data['user_id'] = Auth::id();
		}

		$data['order'] = 1;
		$data['trello_status_id'] = $data['status_id'];

		if($task = TrelloTask::create($data)) {
			$taskDeadlineDate = convertDateFormatWithTimezone($task->created_at, 'Y-m-d H:i:s','l, d M');
			return response()->json([
				'success' => true,
				'task' => $task,
				'deadline_date' => $taskDeadlineDate
			], 200);
		} else {
			return response()->json([
				'success' => false,
			], 200);
		}
	}

	/**
	 * Update trello task
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function trelloTaskUpdate(Request $request) 
	{
		$task = TrelloTask::find($request->id);
		$data = request()->all();

		if(!empty($data['deadline_date'])) {
			$data['deadline_date'] = convertDateFormatWithTimezone($request->deadline_date .'00:00:00', 'd/m/Y H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
		}

		$task->update($data);
		
		if( $request->hasFile('task_attachments') ) {
			$attachments = $request->file('task_attachments');
			foreach ($attachments as $attachment) {
				$attachmentType = CommonUtil::getAttachmentType($attachment);
				if($attachmentType == AttachmentTypes::IMAGE) {
					$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/attachment/images');
				} else if ($attachmentType == AttachmentTypes::VIDEO) {
					$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/attachment/videos');
				} else if($attachmentType == AttachmentTypes::PDF) {
					$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/attachment/pdfs');
				} else {
					$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/attachment');
				}
	            $trelloTaskAttachment = new TrelloTaskAttachment();
	            $trelloTaskAttachment->trello_task_id = $task->id;
		        $trelloTaskAttachment->attachment = $attachmentName;
		        $trelloTaskAttachment->type = $attachmentType;
		        $trelloTaskAttachment->save();
			}
		}

		$peoples = [];
		if(isset($request->peoples)) {
			$peoples = explode(',', $request->peoples);
		}
		$task->users()->sync($peoples);

		$categories = [];
		if(isset($request->categories)) {
			$categories = explode(',', $request->categories);
		}
		$task->categories()->sync($categories);

		if(!empty($task)) {
			return response()->json([
				'success' => true,
				'status_id' => $task->trello_status_id
			], 200);
		} else {
			return response()->json([
				'success' => false,
			], 200);
		}
	}

	/**
	 * Get trello task details
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function getTrelloTaskDetails(Request $request) 
	{
		$task = TrelloTask::find($request->trello_task_id);
		if(isset($task) && !empty($task)) {
			$view = view('seller.trello_board.component.trello_task_details', compact('task'))->render();
	        return response()->json([
	            'success' => true,
	            'view' => $view,
	        ], 200);
		} else {
			return response()->json([
	            'success' => false
	        ], 200);
		}	
	}

	/**
	 * Get trello task comments
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function getTrelloTaskComments(Request $request) 
	{
		$task = TrelloTask::find($request->trello_task_id);
		if(!empty($task) && !empty($task->comments)) {
			$taskComments = $task->comments->whereNull('parent_id');
		}
		if(isset($taskComments) && !empty($taskComments)) {
			$view = view('seller.trello_board.component.trello_task_comment', compact('taskComments'))->render();
	        return response()->json([
	            'success' => true,
	            'view' => $view,
	        ], 200);
		} else {
			return response()->json([
	            'success' => false
	        ], 200);
		}	
	}

	/**
	 * Delete trello task attachment
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function deleteTrelloTaskAttachment(Request $request) 
	{
		$taskAttachment = TrelloTaskAttachment::find($request->id);
		CommonUtil::removeFile($taskAttachment->attachment);
		$taskAttachment->delete();

		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function destroyTrelloTask($id) 
	{
		$task = TrelloTask::find($id);
		if($task) {
			$task->categories()->detach();
			$task->users()->detach();
			if(!empty($task->comments)) {
				foreach($task->comments as $comment) {
					if(!empty($comment->replies)) {
						foreach($comment->replies as $reply) {
							if(!empty($reply->attachments)) {
								foreach($reply->attachments as $attachment) {
					                CommonUtil::removeFile($attachment->name);
					                $attachment->delete();   
					            }
							}
							$reply->delete();
						}
					}
					if(!empty($comment->attachments)) {
						foreach($comment->attachments as $attachment) {
			                CommonUtil::removeFile($attachment->name);
			                $attachment->delete();   
			            }
					}
					$comment->delete();
				}
			}
			if(!empty($task->attachments)) {
	            foreach($task->attachments as $attachment) {
	                CommonUtil::removeFile($attachment->attachment);
	                $attachment->delete();   
	            }
	        }

			$task->delete();
		}

		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * update task status.
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function taskUpdateEvent(Request $request) 
	{
		$trelloTask = TrelloTask::findOrFail($request->id);
		if ($request->sort_array) {
			$counter = 1;
			foreach ($request->sort_array as $taskId) {
				TrelloTask::where(['id' => $taskId])->update(['order' => $counter]);
				$counter++;
			}
		}

		if (!isset($data['user_id'])) {
			$data['user_id'] = Auth::id();
		}

		$updatedTask = TrelloTask::updateOrCreate([
			'id' => $request->id,
		], [
			'trello_status_id' => $request->statusId,
		]);

		TrelloBoardLog::updateOrCreate([
			'user_id' => Auth::id(),
			'trello_task_id' => $request->id,
			'status' => $updatedTask->trello_status_id,
			'previous_status' => $trelloTask->trello_status_id
		], [
			'user_id' => Auth::id(),
			'trello_task_id' => $request->id,
			'status' => $updatedTask->trello_status_id,
			'previous_status' => $trelloTask->trello_status_id
		]);

		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * Add trello status
	 *
	 * @param TrelloStatusRequest $request
	 *
	 * @return JsonResponse
	 */
	public function addTrelloStatus(TrelloStatusRequest $request) 
	{
		$data = $request->all();

		if (!isset($data['user_id'])) {
			$data['user_id'] = Auth::id();
		}

		if($trelloStatus = TrelloStatus::create($data)) {
			$trelloBoard = TrelloBoard::find($trelloStatus->trello_board_id);
			$taskStatuses = TrelloStatus::where('trello_board_id', $trelloStatus->trello_board_id)->orderBy('order', 'asc')->get();
			$taskStatusIds = TrelloStatus::where('trello_board_id', $trelloBoard->id)->orderBy('order', 'asc')->pluck('title', 'id')->toArray();

			$taskStatusIds = json_encode(array_keys($taskStatusIds));
			
			return view('seller.trello_board.status_data', compact('taskStatuses', 'trelloBoard', 'taskStatusIds'));
		}

		return response()->json([
			'success' => false,
		], 200);

	}

	/**
	 * Get trello status data
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function editTrelloStatus($id) 
	{
		$taskStatus = TrelloStatus::find($id);
		$data = [];
		if(!empty($taskStatus)) {
			$data['id'] = $taskStatus->id;
			$data['title'] = $taskStatus->title;
		}
		if(!empty($data)) {
			return response()->json([
				'success' => true,
				'data' => $data
			], 200);
		} else {
			return response()->json([
				'success' => false,
			], 200);
		}
	}

	/**
	 * Update trello status
	 *
	 * @param TrelloStatusRequest $request
	 *
	 * @return JsonResponse
	 */
	public function updateTrelloStatus(TrelloStatusRequest $request) 
	{
		$taskStatus = TrelloStatus::find($request->id);
		$data       = request()->all();
		$taskStatus->update($data);
		if(!empty($data)) {
			return response()->json([
				'success' => true,
				'data'    => [
					'id'    => $taskStatus->id,
					'title' => $taskStatus->title,
				],
			], 200);
		} else {
			return response()->json( [
				'success' => false,
			], 200 );
		}
	}

	/**
	 * Delete trello status
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function destroyTrelloStatus($id) 
	{
		$trelloStatus = TrelloStatus::find($id);
		if($trelloStatus) {
			if(!empty($trelloStatus->tasks)) {
				foreach($trelloStatus->tasks as $task) {
					$task->categories()->detach();
					$task->users()->detach();
					if(!empty($task->comments)) {
						foreach($task->comments as $comment) {
							if(!empty($comment->replies)) {
								foreach($comment->replies as $reply) {
									if(!empty($reply->attachments)) {
										foreach($reply->attachments as $attachment) {
							                CommonUtil::removeFile($attachment->name);
							                $attachment->delete();   
							            }
									}
									$reply->delete();
								}
							}
							if(!empty($comment->attachments)) {
								foreach($comment->attachments as $attachment) {
					                CommonUtil::removeFile($attachment->name);
					                $attachment->delete();   
					            }
							}
							$comment->delete();
						}
					}
					if(!empty($task->attachments)) {
			            foreach($task->attachments as $attachment) {
			                CommonUtil::removeFile($attachment->attachment);
			                $attachment->delete();   
			            }
			        }

					$task->delete();
				}
			}
			$trelloStatus->delete();
		}

		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * update task status Order.
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function taskStatusUpdateEvent(Request $request) 
	{
		if ($request->sort_array) {
			$counter = 1;
			foreach ($request->sort_array as $taskId) {
				TrelloStatus::where(['id' => $taskId])->update(['order' => $counter]);
				$counter++;
			}
		}

		return response()->json([
			'success' => true,
		], 200);
	}

	/**
	 * Get trello board categories
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function getTrelloBoardCategoryList(Request $request) 
    {
    	$trelloBoardCategoryQuery = TrelloBoardCategory::where('trello_board_id', $request->trello_board_id);

    	if(!empty($request->search_text)) {
    		$searchKeyword = $request->search_text;
    		$trelloBoardCategoryQuery->where('title', 'LIKE', "%{$searchKeyword}%");
    	}

    	$trelloBoardCategories = $trelloBoardCategoryQuery->get();

    	$trelloTaskCategoryIds = [];
    	if($request->trello_task_id) {
    		$trelloTask = TrelloTask::findOrFail($request->trello_task_id);
    		$trelloTaskCategoryIds = $trelloTask->categories->pluck('id', 'title')->toArray();
    	}

        if(!empty($trelloBoardCategories)) {
			$view = view('seller.trello_board.component.trello_board_categories', compact('trelloBoardCategories', 'trelloTaskCategoryIds'))->render();
	        return response()->json([
	            'success' => true,
	            'view' => $view,
	        ], 200);
		} else {
			return response()->json([
	            'success' => false
	        ], 200);
		}	
    }

    /**
	 * Get people list
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function getPeopleList(Request $request) 
    {
    	$trelloBoardUserIds = [];
    	if($request->trello_board_id) {
    		$trelloBoard = TrelloBoard::findOrFail($request->trello_board_id);
    		$trelloBoardUserIds = $trelloBoard->users->pluck('id', 'name')->toArray();
    	}
    	
    	$trelloTaskUserIds = [];
    	if($request->trello_task_id) {
    		$trelloTask = TrelloTask::findOrFail($request->trello_task_id);
    		$trelloTaskUserIds = $trelloTask->users->pluck('id', 'name')->toArray();
    	}

        $users = User::query();

        if(!empty($request->search_text)) {
    		$searchKeyword = $request->search_text;
    		$users->where('name', 'LIKE', "%{$searchKeyword}%")->orWhere('last_name', 'LIKE', "%{$searchKeyword}%");
    	}

    	$users = $users->paginate(10);

    	if(!empty($users)) {
    		$view = view('seller.trello_board.component.people_list', compact('users', 'trelloBoardUserIds'))->render();
	    	if(isset($request->from_modal)) {
	    		$view = view('seller.trello_board.component.people_list_for_modal', compact('users', 'trelloTaskUserIds'))->render();
	    	}
	    	
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

    /**
	 * Add trello task comments
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
    public function addTrelloTaskComment(Request $request) 
    {
        $taskComment = new TrelloTaskComment();
        $taskComment->trello_task_id = $request->task_id;
        $taskComment->message = $request->input('body');
        $taskComment->user_id = Auth::id();

        if($request->has('parent_id')) {
            $parentId = $request->input('parent_id');
            $taskComment->parent_id = $parentId;
        }

        $taskComment->save();

        if(!empty($taskComment)) {
        	if($request->hasFile('attachment')) {
				$attachments = $request->file('attachment');
				foreach ($attachments as $attachment) {
					$attachmentType = CommonUtil::getAttachmentType($attachment);
					if($attachmentType == AttachmentTypes::IMAGE) {
						$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/comment/attachment/images');
					} else if ($attachmentType == AttachmentTypes::VIDEO) {
						$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/comment/attachment/videos');
					} else if($attachmentType == AttachmentTypes::PDF) {
						$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/comment/attachment/pdfs');
					} else {
						$attachmentName = CommonUtil::uploadFileToFolder($attachment, 'trello_board/task/comment/attachment');
					}
		            $trelloTaskCommentAttachment = new TrelloTaskCommentAttachment();
		            $trelloTaskCommentAttachment->trello_task_comment_id = $taskComment->id;
			        $trelloTaskCommentAttachment->name = $attachmentName;
			        $trelloTaskCommentAttachment->type = $attachmentType;
			        $trelloTaskCommentAttachment->save();
				}
			}
        }
        
        return response()->json([
			'success' => true,
			'comment' => new TrelloTaskCommentResource($taskComment),
		], 200);
	}

	/**
	 * Create trello board and store trello board id for statuses and tasks
	 */
	public function trelloBoardIdStoreScript() 
	{
		$users = User::has('trelloStatuses')->get();
		foreach($users as $key => $user) {
			$board = TrelloBoard::create([
				'user_id' => $user->id,
				'title' => 'Default Board'
			]);
			$board->users()->syncWithoutDetaching($user->id);
			foreach($user->trelloStatuses as $key => $trelloStatus) {
				$status = TrelloStatus::find($trelloStatus->id);
				if(isset($status)) {
					$status->trello_board_id = $board->id;
					$status->save();

					if(!empty($status->tasks)) {
						foreach($status->tasks as $task) {
							$task = TrelloTask::find($task->id);
							$task->trello_board_id = $board->id;
							$task->save();
						}
					}	
				}	
			}
		}	
	}

	/**
	 * User Task Board status get
	 *
	 * @param string $id
	 *
	 * @return JsonResponse
	 */
	public function userTaskBoardStats(string $id) {
		MetaTag::set('title', 'Rank Up - '. __('User Task Board Stats'));
		MetaTag::set('description', 'Rank Up  User Task Board Stats Page');
		MetaTag::set('image', asset('assets/images/rank-up-logo.svg'));

		$trelloBoard = TrelloBoard::find((int) getDecrypted($id));

		if(empty($trelloBoard)) {
			return redirect()->back();
		}
		
		$todayDate = getCarbonTodayEndDateTimeForUser();
		$dateBeforeWeek = $todayDate->clone()->subDays(6)->format('Y-m-d H:i:s');
		$trelloBoardWeekCount = TrelloBoard::where('created_at','>=',$dateBeforeWeek)->where('created_at', '<=', $todayDate)->whereHas('users', function ($query) {
			$query->where('user_id', Auth::User()->id);
		})->count();
		$trelloBoardWeekUpdateCount = TrelloBoard::where('updated_at','>=',$dateBeforeWeek)->where('updated_at', '<=', $todayDate)->whereHas('users', function ($query) {
			$query->where('user_id', Auth::User()->id);
		})->count();
		$trelloBoardWeekColumnCount = TrelloStatus::where('trello_board_id', $trelloBoard->id)->where('created_at','>=',$dateBeforeWeek)->where('created_at', '<=', $todayDate)->count();
		$trelloBoardWeekUpdateColumnCount = TrelloStatus::where('trello_board_id', $trelloBoard->id)->where('updated_at','>=',$dateBeforeWeek)->where('updated_at', '<=', $todayDate)->count();

		$trelloStatuses = TrelloStatus::where('trello_board_id', $trelloBoard->id)->get();

		$taskMoves = TrelloBoardLog::select('previous_status', 'user_id', 'status', DB::raw('count(*) as move_count'))
		    ->where('user_id', Auth::id())
		    ->groupBy('previous_status', 'user_id', 'status')
		    ->get();

		$taskMoveCount = [];
		$test = [];
		foreach($taskMoves as $taskMove) {
		    $status = $taskMove->status;
		    $previousStatus = $taskMove->previous_status;
		    $taskMoveCount[$status][$previousStatus] = $taskMove->move_count;
		}

		return view('seller.trello_board.user-trello-board-stats',compact('trelloBoardWeekCount','trelloBoardWeekColumnCount', 'trelloBoardWeekUpdateColumnCount', 'trelloBoardWeekUpdateCount', 'trelloStatuses', 'taskMoveCount'));
	}

	/**
	 * Delete trollo board
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 */
	public function destroy($id) 
	{
		$trelloBoard = TrelloBoard::find($id);
		if($trelloBoard) {
			$trelloBoard->users()->detach();
			$trelloBoard->categories()->delete();
			if(!empty($trelloBoard->trelloStatuses)) {
				foreach($trelloBoard->trelloStatuses as $status) {
					$status->delete();
				}
			}
			if(!empty($trelloBoard->trelloTasks)) {
				foreach($trelloBoard->trelloTasks as $task) {
					$task->categories()->detach();
					$task->users()->detach();
					if(!empty($task->comments)) {
						foreach($task->comments as $comment) {
							if(!empty($comment->replies)) {
								foreach($comment->replies as $reply) {
									if(!empty($reply->attachments)) {
										foreach($reply->attachments as $attachment) {
							                CommonUtil::removeFile($attachment->name);
							                $attachment->delete();   
							            }
									}
									$reply->delete();
								}
							}
							if(!empty($comment->attachments)) {
								foreach($comment->attachments as $attachment) {
					                CommonUtil::removeFile($attachment->name);
					                $attachment->delete();   
					            }
							}
							$comment->delete();
						}
					}
					if(!empty($task->attachments)) {
			            foreach($task->attachments as $attachment) {
			                CommonUtil::removeFile($attachment->attachment);
			                $attachment->delete();   
			            }
			        }
			        $task->delete();
				}
			}
			
			$trelloBoard->delete();
		}

		$trelloBoardCount = TrelloBoard::whereHas('users', function ($query) {
			    $query->where('user_id', Auth::User()->id);
			})->count();

		return response()->json([
			'success' => true,
			'trello_board_count_text' => __('You currently have') .' '. $trelloBoardCount .' '. __('boards')
		], 200);
	}
}
<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\UserPerformanceRadialSetting;
use App\Models\UserTask;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
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
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $userId = Auth::User()->id;
        $tasks = Auth::User()->tasks()->pluck('id');
        $taskArr = $postTaskIds = [];
        if ($tasks->isNotEmpty()) {
            $taskArr = $tasks->toArray();
        }
      
        if (!empty($data) && isset($data['title'])) {
            if (isset($data['id'])) {
                $postTaskIds = $data['id'];
            }
            foreach ($data['title'] as $key => $title) {
                if (isset($data['id']) && isset($data['id'][$key])) {
                    $task = Task::find($data['id'][$key]);
                    $repeatDays = '';
                    $task->repeat_monday = $task->repeat_tuesday = $task->repeat_wednesday = $task->repeat_thursday = $task->repeat_friday = $task->repeat_saturday = $task->repeat_sunday = 0;
                } else {
                    $task = new Task();
                    $task->user_id = $userId; 
                }
                $task->title = $title;
                if (isset($data['repeat_days']) && isset($data['repeat_days'][$key])) {
                    $repeatDays = implode(',', $data['repeat_days'][$key]);
                    $task->repeat_days = $repeatDays;
                    foreach ($data['repeat_days'][$key] as $day) {
                        switch ($day) {
                            case 'mon':
                                $task->repeat_monday = 1;
                                break;
                            case 'tue':
                                $task->repeat_tuesday = 1;
                                break;
                            case 'wed':
                                $task->repeat_wednesday = 1;
                                break;
                            case 'thu':
                                $task->repeat_thursday = 1;
                                break;
                            case 'fri':
                                $task->repeat_friday = 1;
                                break;
                            case 'sat':
                                $task->repeat_saturday = 1;
                                break;
                            default:
                                $task->repeat_sunday = 1;
                        }
                    }
                }
                $task->save();
            }
        }
        // remove task data from database 
        if (!empty($taskArr)) {
            $deleteIds = array_diff($taskArr, $postTaskIds);
            if (!empty($deleteIds)) {
                Task::destroy($deleteIds);
            }
        }

        // Store Performance radial circle setting
        if (isset($data['no_of_clients'])) {
            $userPerformanceRadialSetting = UserPerformanceRadialSetting::where(['user_id' => $userId, 'is_team' => $data['is_team']])->first();

            if ($userPerformanceRadialSetting && $userPerformanceRadialSetting->count()) {
                $userPerformanceRadialSetting->no_of_clients = $data['no_of_clients'] * UserPerformanceRadialSetting::CLIENT_SLICE;
                $userPerformanceRadialSetting->no_of_distributors = $data['no_of_distributors'] * UserPerformanceRadialSetting::DISTRIBUTOR_SLICE;
                $userPerformanceRadialSetting->is_team = $data['is_team'];
            } else {
                $userPerformanceRadialSetting = new UserPerformanceRadialSetting();
                $userPerformanceRadialSetting->no_of_clients = $data['no_of_clients'] * UserPerformanceRadialSetting::CLIENT_SLICE;
                $userPerformanceRadialSetting->no_of_distributors = $data['no_of_distributors'] * UserPerformanceRadialSetting::DISTRIBUTOR_SLICE;
                $userPerformanceRadialSetting->user_id = $userId;
                $userPerformanceRadialSetting->is_team = $data['is_team'];
            }
            if ($data['is_team'] == 1 && !empty($data['custom_no_of_distributors'])) {
                $userPerformanceRadialSetting->no_of_distributors = $data['custom_no_of_distributors'];
            } else if ($data['is_team'] == 0 && !empty($data['custom_no_of_distributors'])) {
                $userPerformanceRadialSetting->no_of_distributors = $data['custom_no_of_distributors'];
            }

            $userPerformanceRadialSetting->save();
        }

        return response()->json([
            'success' => true,
            'is_team' => isset($data['is_team']) ? $data['is_team'] : 0
        ], 200);
    }

    /**
     * Get task data
     *
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function taskData(Request $request)
    {
        $todayStartDate = getCarbonTodayForUser();
        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');
        $todayEndDateForFilter = $todayDate->clone()->format('Y-m-d H:i:s');
        $tasks = $completedTasks = [];
        $completedTaskDates = '';
        $todayDay = getTodayDayForUser();
        $tasks = Task::where(['user_id' => Auth::User()->id, 'repeat_'.$todayDay => 1])->get();
        
        $todayTask = UserTask::where(['user_id' => Auth::User()->id])
            ->where('task_date', '>=', $todayStartDateForFilter)
            ->where('task_date', '<', $todayEndDateForFilter)->first();

        if(!empty($todayTask)) {
            $completedTasks = (array) json_decode($todayTask->tasks);
        }

        $start = convertDateFormatWithTimezone($todayStartDate->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $end = convertDateFormatWithTimezone($todayStartDate->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

        $completedTaskDatesData = UserTask::where(['user_id' => Auth::User()->id, 'is_complete' => 1])
            ->where('task_date', '>=', $start)
            ->where('task_date', '<=', $end)->pluck('task_date', 'id');

        $completedTaskDates = array();
        if(!empty($completedTaskDatesData)) {
            foreach($completedTaskDatesData as $completedTaskDate) {
                $completedTaskDates[] = convertDateFormatWithTimezone($completedTaskDate, 'Y-m-d H:i:s','d/m/Y','CRM-TO-FRONT').",,blue";
            }
        }
        $completedTaskDates = json_encode($completedTaskDates);
        $view = view('seller.dashboard.dailies.task', compact('tasks', 'completedTasks'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ], 200);
    }

    /**
     * Get task list
     *
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function getList(Request $request)
    {
        $userId = Auth::User()->id;
        $tasks = Task::where(['user_id' => $userId])->get();
        $data = $request->all();
        $userPerformanceRadialSetting = UserPerformanceRadialSetting::where(['user_id' => $userId, 'is_team' => $data['is_team']])->first();
        $userPerformanceRadialSettingArr = ['no_of_clients' => 1, 'no_of_distributors' => 1];
        if ($userPerformanceRadialSetting && $userPerformanceRadialSetting->count()) {
            $userPerformanceRadialSettingArr['no_of_clients'] = $userPerformanceRadialSetting->no_of_clients / UserPerformanceRadialSetting::CLIENT_SLICE;
            if($userPerformanceRadialSetting->no_of_distributors > 30) {
                $userPerformanceRadialSettingArr['no_of_distributors'] = $userPerformanceRadialSetting->no_of_distributors;
            } else {
                $userPerformanceRadialSettingArr['no_of_distributors'] = $userPerformanceRadialSetting->no_of_distributors / UserPerformanceRadialSetting::DISTRIBUTOR_SLICE;
            }
        }

        $view = view('seller.dashboard.dailies._dailies_tasks_list', compact('tasks', 'data', 'userPerformanceRadialSettingArr', 'userPerformanceRadialSetting'))->render();
        return response()->json(['task_html' => $view]);
    }

    /**
     * Update user tasks.
     *
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function userTaskUpdate(Request $request)
    {
        $userId = Auth::User()->id;
        $todayDay = getTodayDayForUser();
        $tasks = Task::where(['user_id' => Auth::User()->id, 'repeat_' . $todayDay => 1])->get();
        if ($request) {
            $todayDate = getCarbonTodayEndDateTimeForUser();
            $todayTask = UserTask::where(['user_id' => $userId])
                ->where('task_date', '>=', $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s'))
                ->where('task_date', '<', $todayDate->clone()->format('Y-m-d H:i:s'))->first();
            if (!empty($todayTask)) {
                $existingTasks = (array) json_decode($todayTask->tasks);
                $newTask = true;
                if (isset($existingTasks[$request->id])) {
                    unset($existingTasks[$request->id]);
                    $newTask = false;
                }
                $taskArr = [];

                foreach ($existingTasks as $existingTask) {
                    $taskArr[$existingTask->id] = (array) $existingTask;
                }
                if ($newTask) {
                    $taskArr[$request->id] = [
                        'id' => $request->id,
                        'created_at' => getCarbonNowForUser()->format('Y-m-d H:i:s'),
                    ];
                }

                if ($tasks->count() == count($taskArr)) {
                    $todayTask->is_complete = 1;
                } else {
                    $todayTask->is_complete = 0;
                }
                $todayTask->tasks = $taskArr;
                $todayTask->save();
            } else {
                UserTask::create([
                    'user_id' => $userId,
                    'tasks' => json_encode([$request->id => [
                        'id' => $request->id,
                        'created_at' => getCarbonNowForUser()->format('Y-m-d H:i:s'),
                    ]]),
                    'task_date' => $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s'),
                    'is_complete' => ($tasks->count() == 1) ? 1 : 0,
                ]);
            }
        }
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Get completed task dates
     *
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function getCompletedTaskDates(Request $request)
    {
        $userId = Auth::User()->id;
        $selectedMonthYear = Carbon::parse($request->year . '-' . $request->month . '-1');

        $start = convertDateFormatWithTimezone($selectedMonthYear->clone()->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $end = convertDateFormatWithTimezone($selectedMonthYear->clone()->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

        $completedTaskDates = UserTask::where(['user_id' => $userId, 'is_complete' => 1])
            ->where('task_date', '>=', $start)
            ->where('task_date', '<', $end)->pluck('task_date', 'id');

        $returnArr = [];
        if (!empty($completedTaskDates)) {
            foreach ($completedTaskDates as $completedTaskDate) {
                $returnArr[] = convertDateFormatWithTimezone($completedTaskDate, 'Y-m-d H:i:s', 'd/m/Y', 'CRM-TO-FRONT') . ",,blue";
            }
        }
        return response()->json([
            'success' => true,
            'data' => $returnArr,
        ], 200);
    }
}
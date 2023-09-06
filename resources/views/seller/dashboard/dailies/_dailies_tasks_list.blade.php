@if($data['type'] == 'add-personal-goal' || $data['type'] == 'add-team-goal')
    <div class="performance-circle mb-4">
        <label class="form-label mb-4 text-white">{{ __('Nº of clients') }}</label>
        <div class="range">
            <input type="range" min="1" max="10" steps="1" value="{{ $userPerformanceRadialSettingArr['no_of_clients'] }}" name="no_of_clients">
        </div>
        <ul class="range-labels">
            <li class="active selected">2</li>
            <li>4</li>
            <li>6</li>
            <li>8</li>
            <li>10</li>
            <li>12</li>
            <li>14</li>
            <li>16</li>
            <li>18</li>
            <li>20</li>
        </ul>

        <div class="team-distributors-goal">
            <label class="form-label mb-4 mt-3 text-white">{{ __('Nº of distributors') }}</label>
            @if(isset($userPerformanceRadialSetting->no_of_distributors) )
                @if($userPerformanceRadialSetting->no_of_distributors%3 != 0 || $userPerformanceRadialSetting->no_of_distributors > 27 )
                    <input type="text" class="maxNoOfDistributors mb-4 mt-3" name="custom_no_of_distributors" value="{{ $userPerformanceRadialSetting->no_of_distributors }}"/>
                @else
                    <input type="text" class="maxNoOfDistributors mb-4 mt-3" name="custom_no_of_distributors" style="display: none" value="{{ $userPerformanceRadialSettingArr['no_of_distributors'] > 10 ? $userPerformanceRadialSettingArr['no_of_distributors'] : '' }}"/>
                @endif
            @else
                <input type="text" class="maxNoOfDistributors mb-4 mt-3" name="custom_no_of_distributors" style="display: none" value="{{ $userPerformanceRadialSettingArr['no_of_distributors'] > 10 ? $userPerformanceRadialSettingArr['no_of_distributors'] : '' }}"/>
            @endif
        </div>
        <div class="range-distributors">
            @if(isset($userPerformanceRadialSetting->no_of_distributors) )
                @php 
                    $rangeDistributor = ($userPerformanceRadialSetting->no_of_distributors%3 != 0 || $userPerformanceRadialSetting->no_of_distributors > 27) ? 10 : $userPerformanceRadialSettingArr['no_of_distributors']
                @endphp
                <input type="range" min="1" max="10" steps="1" value="{{ $rangeDistributor }}" name="no_of_distributors">
            @else
                <input type="range" min="1" max="10" steps="1" value="{{ $userPerformanceRadialSettingArr['no_of_clients'] }}" name="no_of_distributors">
            @endif
        </div>
        <ul class="range-distributors-labels">
            <li class="active selected">3</li>
            <li>6</li>
            <li>9</li>
            <li>12</li>
            <li>15</li>
            <li>18</li>
            <li>21</li>
            <li>24</li>
            <li>27</li>
            @if(isset($data['is_team']) && $data['is_team'] == 1)
                <li class="custom-range-li">{{ __('Custom') }}</li>
            @else
                <li class="default-range-li">30</li>
            @endif
        </ul>
    </div>
@else
    @if(isset($tasks) && $tasks->isNotEmpty() && $data['addNew'] == 'false')
        @foreach($tasks as $key => $task)
            <div class="dailies-task" data-raw-id="{{ $key + 1 }}">
                <div class="dailies-task-header mb-3">
                    <h6 class="task-title">{{ __('Task') }} #{{ $key + 1}}</h6>
                    <a href="javascript:void(0)" class="task-close-icon">
                        <i class="feather-x"></i>
                    </a>
                </div>
                <div class="dailies-task-input mb-3">
                  <input type="text" class="form-control task-title-input" placeholder="{{ __('Title') }}" name="title[{{$key}}]" value="{{ $task->title }}">
                    <input type="hidden" name="id[{{$key}}]" value="{{ $task->id }}">
                </div>
                <div class="dailies-task-week ">
                    <h6>{{ __('Repeat') }}:</h6>
                    <div class="week">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="sun" name="repeat_days[{{$key}}][]" {{ $task->repeat_sunday ? "checked" : "" }}>
                            <label class="form-check-label" for="sunday">{{ getDayName('S')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="mon" name="repeat_days[{{$key}}][]" {{ $task->repeat_monday ? "checked" : "" }}>
                            <label class="form-check-label" for="monday">{{ getDayName('M')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="tue" name="repeat_days[{{$key}}][]" {{ $task->repeat_tuesday ? "checked" : "" }}>
                            <label class="form-check-label" for="tuesday">{{ getDayName('T')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="wed" name="repeat_days[{{$key}}][]" {{ $task->repeat_wednesday ? "checked" : "" }}>
                            <label class="form-check-label" for="wednesday">{{ getDayName('W')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="thu" name="repeat_days[{{$key}}][]" {{ $task->repeat_thursday ? "checked" : "" }}>
                            <label class="form-check-label" for="thursday">{{ getDayName('t')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="fri" name="repeat_days[{{$key}}][]" {{ $task->repeat_friday ? "checked" : "" }}>
                            <label class="form-check-label" for="friday">{{ getDayName('F')[App::currentLocale()] }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="sat" name="repeat_days[{{$key}}][]" {{ $task->repeat_saturday ? "checked" : "" }}>
                            <label class="form-check-label" for="saturday">{{ getDayName('s')[App::currentLocale()] }}</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="dailies-task" data-raw-id="{{ $data['totalRow'] + 1}}">
            <div class="dailies-task-header mb-3">
                <h6 class="task-title">{{ __('Task') }} #{{$data['totalRow'] + 1 }}</h6>
                <a href="javascript:void(0)" class="task-close-icon">
                    <i class="feather-x"></i>
                </a>
            </div>
            <div class="dailies-task-input mb-3">
                <input type="text" class="form-control task-title-input" placeholder="{{ __('Title') }}" name="title[{{$data['totalRow']}}]" value="">
            </div>
            <div class="dailies-task-week">
                <h6>{{ __('Repeat') }}:</h6>
                <div class="week">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sun" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="sunday">{{ getDayName('S')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="mon" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="monday">{{ getDayName('M')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="tue" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="tuesday">{{ getDayName('T')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="wed" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="wednesday">{{ getDayName('W')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="thu" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="thursday">{{ getDayName('t')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="fri" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="friday">{{ getDayName('F')[App::currentLocale()] }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sat" name="repeat_days[{{$data['totalRow']}}][]">
                        <label class="form-check-label" for="saturday">{{ getDayName('s')[App::currentLocale()] }}</label>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

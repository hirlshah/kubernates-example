<div class="container-fluid mb-4">
    <div class="row">
        @php $counter = 1; @endphp
        @foreach($events as $key => $event)
            <div class="col-12 col-sm-12 col-md-6 col-xxl-4">
                <div class="card border-0 my-3" style="min-height: calc(100% - 16px);">
                    <div class="event-image" style=@if(isset($event->image) && Storage::disk('public')->exists($event->image)) "background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}); min-height: 200px;" @else "background-image: url({{ asset(config('app.rankup.company_default_image_file')) }}); min-height: 200px;" @endif></div>
                  
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <h6 class="card-title mb-4">
                                    <a href="{{ route('event-detail', $event->slug) }}">{{ $event->name }}</a>
                                </h6>
                            </div>
                            <div class="col-md-3 text-end">
                                <?php
                                    $isOwner = $event->user_id === Auth::id();
                                ?>
                                @if($isOwner)
                                    <a class="btn btn-edit p-1 modal-popup-edit-event" data-id="{{ $event->id }}"><i class="feather-edit fs-20"></i></a>
                                    <a href="javascript:;" data-url=" {{ url( '/seller/event-delete/' . $event->id ) }}" class="modal-popup-delete-event btn btn-delete p-1"><i class="feather-trash-2 fs-20"></i></a>
                                @endif
                            </div>
                        </div>
                        <p class="grey-666666">{{__('Organized by')}}: @if(!empty($event->user)) {{ $event->user->getFullName() }} @endif</p>
                        <p class="card-text grey-666666 fs-14 mb-3" style="min-height: 64px">{{ $event->content }}</p>
                        @php
                        $url = route('frontend.event.details',$event->slug);
                        if($event->user_id != Auth::user()->id){
                            $url .= '?referral='.Auth::user()->referral_code;
                        }
                        @endphp
                        <h5>{{__('URL')}}</h5>
                        <div class="event-code mb-3">
                            <input value="{{ $url }}" type="text" class="event-link" style="display: inline-block;">
                            <i class="feather-copy blue fs-20 copy-event-link" id="copy_{{ $url }}" data-href="{{ $url }}"></i>
                            <div class="tooltiptext" style="display: none"><span></span></div>
                        </div>
                        <div class="tags mb-3" style="min-height: 90px;">
                            @foreach($event->tags as $tag)
                                <span class="">{{ $tag->name }}</span>
                            @endforeach
                            @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                                <span>{{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','d M  Y') }}</span>
                                <span>{{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','H:i') }}h</span>
                            @endif
                        </div>
                        <a href="{{ route('event-detail', $event->slug) }}" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">{{__('See Details')}}</a>
                        @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                            <a href="javascript:void(0)" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2 download-ics" data-id="{{ $event->id }}" data-url="{{ route('seller.event.download-ics', $event->id) }}" name="ics">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        @endif
                        <?php
                            $reps = $event->reps()->get()->toArray();
                            $repIds = array_column($reps, 'id');
                            $presenceConfirmed = in_array(Auth::id(), $repIds);
                        ?>

                        @if(!$isOwner)
                            <br>
                            @if(!$event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first())
                                <button data-href="{{ route('seller.event.confirm-presence', ['event'=>$event->id]) }}" data-id="{{ $event->id }}" id="confirm-presence-{{ $event->id }}" class="btn btn-blue fs-12 mw-max-content  event-reps-add-btn">{{__('Confirm My Presence')}}</button>
                                <span id="confirmed-presence-{{ $event->id }}" style="display: none">{{__('Your presence has been confirmed.')}}</span>
                            @elseif(!checkIfEventIsPastCurrentTime($event))
                                <span>{{__('Your presence has been confirmed.')}}</span>
                            @elseif(checkIfEventIsPastCurrentTime($event))
                                <a type="button" id="zoom_meeting_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('seller.event.confirm-presence',$event->id) }}">{{__('Zoom Meeting')}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @if($counter == 8)
                {{-- <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3 bg-repeat-n bg-cover bg-center height-100-30px" style="background-image: url({{ asset(config('app.rankup.company_default_image_file')) }});">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <h6 class="card-title text-white mb-0">{{__('Conférence en ligne gratuite')}}</h6>
                                <img src="{{ asset('assets/images/line.png') }}" class="img-fluid" alt="Line">
                                <p class="card-text text-white fs-14 mb-3 lh-lg fw-light">{{__('Lorem ipsum dolor sit amet,consectetur adipiscing elit. Quis nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.')}}</p>
                                <a href="" class="btn btn-dark-yellow fs-16 mw-max-content">{{__('Check calendar')}}</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @endif
            @php $counter++; @endphp
        @endforeach
    </div>
</div>
<div class="container-fluid mt-4 mb-5 a-pagination-links">
    {{$events->links()}}
</div>

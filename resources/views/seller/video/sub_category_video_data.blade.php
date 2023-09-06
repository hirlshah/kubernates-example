@if(!empty($videos) && $videos->count() > 0)
    <ul id="draggble-training-video" class="cursor-move">
        @foreach($videos as $video)
            <li class="sortable-training-video-card" data-id="{{ $video->id }}">
                <div class="row align-items-center gy-3" id="video_{{ $video->id }}">
                    <div class="col-12 d-flex">
                        <h6>{{ $video->title }}</h6>

                        @if(sizeof($video->videoCompleted)) 
                            <i class="ms-2 feather-check-circle text-success fs-22"></i>
                        @endif
                        <i class="ms-auto feather-move text-primary fs-22"></i>
                    </div>

                    <div class="col-9">
                        <p>{{ $video->description }}</p>
                    </div>
                    <div class="col-3 ps-0">
                        <div class="d-flex gap-2 justify-content-end">
                            @if(auth()->user()->id == $video->user_id)
                                <div class="modal-popup-delete-video cursor-pointer" data-id="{{ $video->id }}" data-url="{{ route('videos.destroy', $video->id )}}">
                                    <div class="bg-danger text-center py-2 px-2 rounded-3 lh-1">
                                        <i class="feather-trash-2 text-white"></i>
                                    </div>
                                </div>
                            @endif
                            <div>
                                <div class="bg-primary text-center py-2 px-2 rounded-3 lh-1">
                                    @if(Request::get('category'))
                                        <a href="{{ route('seller.video-detail', ['id' => $video->id])}}?category={{ Request::get('category') }}">
                                            <i class="feather-play text-white"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('seller.video-detail', ['id' => $video->id])}}">
                                            <i class="feather-play text-white"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
            </li>
        @endforeach
    </ul>
@elseif($emptyMsg)
    <div class="no-matching-text">
        <h6>{{ __('Oops.')}}</h6>
        <h6>{{ __('No matching results for this search')}}</h6>
    </div>
@endif
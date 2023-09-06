<div class="container-fluid mb-6">
    <div class="row">
        @foreach($helps as $key => $help)
            <div class="col-12 col-sm-12 col-md-6 col-xl-6 my-3 sortable-video-card" data-id="{{$help->id}}">
                <div class="card border-0">
                    <div class="card-top-img-overflow">
                        @php
                            $url = $help->url;
                            $urlExplode = explode("/",$url);
                        @endphp
                        @if(!empty($urlExplode[3] || !empty($urlExplode[4])))
                        <iframe title="vimeo-player" src="https://player.vimeo.com/video/{{$urlExplode[3]}}?h={{$urlExplode[4]}}" width="100%" height="200" allowfullscreen="" frameborder="0" sandbox="allow-scripts allow-same-origin allow-presentation" layout="responsive">
                        </iframe>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column flex-shrink-0">
                        @if ( Config::get('app.locale') == 'en')
                            <h6 class="card-title mb-6">{{ $help->title_en }}</h6>
                        @else
                            <h6 class="card-title mb-6">{{ $help->title_fr }}</h6>
                        @endif
                        @if(!empty($help->url))
                            <p class="card-text grey-666666 fs-14 mb-3">{{ $help->url }}</p>
                        @endif
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <a href="#modal_play_vimeo" class="modal-popup-vimeo btn btn-light-blue" data-url="https://player.vimeo.com/video/{{$urlExplode[3]}}?h={{$urlExplode[4]}}" data-toggle="modal"><i class="feather-play"></i>  {{__('Play Video')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

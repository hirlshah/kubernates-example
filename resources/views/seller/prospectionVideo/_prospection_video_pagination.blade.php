<div class="container-fluid mb-4">
    <ul class="nav nav-tabs sub-category-nav">
        @foreach($subNewCategories as $key => $subNewCategory)
            <li class="nav-item">
                <button class="position-relative nav-link {{ $filteredSubCategory == $subNewCategory->id ? 'active' : ''}}"  type="button" role="tab" aria-controls="home" aria-selected="false" tabindex="-1" id="prospection-sub-category-filter">
                    {{ $subNewCategory->name }}
                    <input type="radio" class="d-none sub_category-filter" name="sub_category" value="{{$subNewCategory->id}}">
                    @if(in_array($subNewCategory->id, $authCategoryIds))
                        <a href="javascript:void(0);" data-url="{{ route('seller.category.destroy-subCategory', ['prospectionVideo', $subNewCategory->id]) }}" class="modal-popup-delete-sub-category">
                            <i class="feather-trash"></i>
                        </a>
                    @endif
                </button>
            </li>
        @endforeach
    </ul>
</div>
<div class="container-fluid mb-4">
    <div class="row gy-3" id="sortable">
        @foreach($prospectionVideos as $key => $prospection)
            <div class="col-md-6 col-xl-4 sortable-video-card" data-id="{{$prospection->id}}">
                <div class="card h-100 br-10">
                    <div class="card-top-img-overflow">
                        @if(!empty($prospection->video_cover_image) && is_file(public_path("storage/".$prospection->video_cover_image)))
                            <img class="img-fluid" src="{{ App\Classes\Helper\CommonUtil::getUrl($prospection->video_cover_image)}}" alt="" />
                        @else
                            <img class="img-fluid" src="{{ asset(config('app.rankup.company_thumbnail_path')) }}" alt="" />
                        @endif
                        <a href="{{ route('prospection.analytics', $prospection->slug)}}" class="btn btn-blue position-absolute p-2 btn-dark-2" style="right: 1rem;top:1rem; z-index:9;">
                            <i class="feather-activity"></i>
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column flex-shrink-0">
                        <div class="card-title mb-md-4 mb-2 text-truncate">{{ $prospection->title }}</div>
                        @if(!empty($prospection->description)) <p class="card-text grey-666666 fs-14 mb-md-3 mb-2 text-truncate"> {{ $prospection->description }} </p>  @endif 
                        @if(isset($prospection->user) && !empty($prospection->user))
                            <div class="card-title mb-md-4 mb-2 d-flex align-items-center gap-1"> 
                                <h6>{{__('Added by')}}</h6> 
                                <div class="user-profile-dropdown-wrapper">
                                    <a class="user-profile" id="user-profile-dropdown-button-{{ $prospection->user->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-user-id="{{ $prospection->user->id }}">{{ $prospection->user->name }} {{ $prospection->user->last_name }}</a>
                                    <div class="dropdown-menu p-3 user-profile-dropdown" aria-labelledby="user-profile-dropdown-button-{{ $prospection->user->id }}">
                                        <div class="text-center">
                                            @if(isset($prospection->user) && !empty($prospection->user) && is_file(public_path("storage/".$prospection->user->profile_image)))
                                                <img class="mw-75 br-50 mb-2 prospection-user-profile" src="{{App\Classes\Helper\CommonUtil::getUrl($prospection->user->profile_image) }}"  alt="" />
                                            @else
                                                <img class="mw-75 br-50 mb-2 prospection-user-profile" src="{{ asset('assets/images/new-user-profile.png')}}"  alt="" />
                                            @endif
                                            <h6 class="mb-2 prospection-user-name">{{ $prospection->user->name }} {{ $prospection->user->last_name }}</h6>
                                            <p class="mb-2 d-flex align-items-center justify-content-center prospection-user-email"><i class="feather-mail blue me-2"></i>{{ $prospection->user->email}}</p>
                                            <a href="{{ route('seller.member.profile',$prospection->user->id) }}" class="btn btn-blue user-profile-button">
                                                @if(app()->getLocale() == "fr")
                                                {{__('See \'s profile')}} {{ $prospection->user->name }} {{ $prospection->user->last_name }}
                                                @else
                                                {{__('See')}} {{ $prospection->user->name }} {{ $prospection->user->last_name }}{{__("'s profile")}}
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        @endif
                        @if(isset($prospection->category) && !empty($prospection->category))
                            <div class="tags">
                                <span>
                                    {{ ucwords($prospection->category->name) }}
                                </span>
                            </div>
                        @endif
                        <div class="mt-auto">
                            @if(auth()->user()->id == $prospection->user_id)
                                <a class="btn btn-edit modal-popup-edit-prospection-video px-1" data-id="{{ $prospection->id }}"><i class="feather-edit fs-20"></i></a>
                                <a href="javascript:;" data-url=" {{ url( '/seller/prospection/' . $prospection->id ) }}" class="modal-popup-delete-prospection-video btn btn-delete px-1" data-id="{{$prospection->id}}"><i class="feather-trash-2 fs-20"></i></a>
                            @endif
                        </div>
                        <span class="copy-link-tooltip">
                            <button class="modal-popup-video-generate btn btn-light-blue w-100" id="copy_{{$prospection->slug}}" data-href="{{ route('prospection.slug', $prospection->slug) }}?referral={{ Auth::user()->referral_code }}&lang={{ app()->getLocale() }}"><i class="feather-link-2 me-1"></i>{{__('Generate link')}}</button>
                            <span class="copy_generate_link_url">
                                <p class="mb-0">{{__('Copy to clipboard')}}.</p>
                            </span>
                            <input type="text" value="" id="prospection_link_copy" class="d-none">
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="container-fluid mt-4 mb-5 a-pagination-links">
    {{$prospectionVideos->links()}}
</div>

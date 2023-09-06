 <div class="container-fluid mb-4">
    <ul class="nav nav-tabs sub-category-nav">
        @foreach($subNewCategories as $key => $subNewCategory)
            <li class="nav-item">
                <button class="position-relative nav-link {{ $filteredSubCategory == $subNewCategory->id ? 'active' : ''}}" data-bs-toggle="tab"  type="button" role="tab" aria-controls="home" aria-selected="false" tabindex="-1" id="document-sub-category-filter">{{ $subNewCategory->name}}
                    <input type="radio" class="d-none sub_category-filter" name="sub_category" value="{{ $subNewCategory->id}}">
                    @if(in_array($subNewCategory->id, $authCategoryIds))
                        <a href="javascript:void(0);" data-url="{{ route('seller.category.destroy-subCategory', ['document', $subNewCategory->id]) }}"  class="modal-popup-delete-sub-category">
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
        @foreach($documents as $key => $document)
            <div class="col-md-6 col-xl-4 sortable-video-card" data-id="{{$document->id}}">
                <div class="card border-0 h-100">
                    <div class="event-image" style="@if(isset($document->image) && Storage::disk('public')->exists($document->image)) background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($document->image) }}); @else background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }});  @endif min-height: 200px;"></div>
                   
                    <div class="card-body d-flex flex-column flex-shrink-0">
                        <h6 class="card-title mb-md-4 mb-2">{{ $document->title }}</h6>
                        <p class="card-text grey-666666 fs-14 mb-md-3 mb-2">{{ $document->description }}</p>
                        <div class="tags mb-md-3 mb-2">
                            @foreach($document->tags as $tag)
                                <span class="">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            @if($document->document)
                                <a href="{{ url( '/seller/document/' . $document->id.'/download') }}" class="btn btn-light-blue fs-12 p-3"><i class="feather-download"></i> {{__('Download')}}</a>
                            @endif
                            @if(auth()->user()->id == $document->user_id)
                                <div>
                                    <a href="#" data-url="{{url( '/seller/documents/' . $document->id . '/edit' ) }}" data-id="{{$document->id}}" class="btn btn-edit p-3 edit_document"><i class="feather-edit"></i></a>
                                    <a href="javascript:;" data-url="{{ url( '/seller/documents/' . $document->id ) }}" class="modal-popup-delete-document btn btn-delete p-3"><i class="feather-trash-2"></i></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="container-fluid mt-4 mb-5 a-pagination-links">
    {{$documents->links()}}
</div>

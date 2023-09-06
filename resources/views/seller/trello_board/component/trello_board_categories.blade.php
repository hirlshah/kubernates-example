@if(!empty($trelloBoardCategories))
    @foreach($trelloBoardCategories as $key => $category)
        <div class="mb-2">
            <input class="category-check categories" id="category_{{ $key }}" type="checkbox" value="{{ $category->id }}" name="categories" @if(in_array($category->id, $trelloTaskCategoryIds)) checked @endif />
            <label class="category-check-label text-white" for="category_{{ $key }}" style="background-color: {{ $category->color }}">
                {{ $category->title }}
            </label>
        </div>
    @endforeach
@endif
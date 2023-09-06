<div id="comments">
    <ul class="comment-list">
        @if(!empty($taskComments))
            @foreach($taskComments as $comment)
                @include('seller.trello_board.component.trello_task_comment_replies', ['comment' => $comment])
            @endforeach
        @endif
    </ul>
</div>

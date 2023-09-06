<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Classes\Helper\CommonUtil;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TrelloTaskCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $attachments = [];
        if(!empty($this->attachments)) {
            foreach($this->attachments as $attachment) {
                $attachments[] = [
                    'type' => $attachment->type,
                    'name' => isset($attachment->name) ? CommonUtil::getUrl($attachment->name) : '',
                    'pdf_img' => asset('images/mime/pdf.svg')
                ];
            }
        }

        $data = [
            'id'            => $this->id,
            'parent_id'     => $this->parent_id,
            'user_name'     => $this->user->name,
            'user_image'    => isset($this->user->thumbnail_image) && Storage::disk('public')->exists($this->user->thumbnail_image) ? CommonUtil::getUrl($this->user->thumbnail_image) : asset('assets/images/profile-1.png'),
            'message'       => $this->message,
            'created_at'    => Carbon::parse($this->created_at)->format('F j, Y \a\t h:ia'),
            'attachments'   => $attachments,
            'trello_status_id' => isset($this->trelloTask) ? $this->trelloTask->trello_status_id : null
        ];

        return $data;
    }
}

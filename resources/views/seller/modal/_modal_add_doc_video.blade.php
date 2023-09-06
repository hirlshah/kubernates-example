<div class="modal fade" id="add_document_video_modal" tabindex="-1" role="dialog" aria-labelledby="add_document_video_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-calendar blue me-3"></i>
                    <h6>{{__('Select Document and Video to add')}}</h6>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h4>{{__('Document')}}</h4>
                        <table class="table table-striped table-hover">
                            <tbody>
                                @foreach($documents as $value)
                                    <tr>
                                        <td>
                                            <label class="w-100">
                                                <input type="checkbox" class="event_documents mx-2" name="documents[]" value="{{$value->id}}" autocomplete="off">
                                                {{$value->title}}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>{{__('Video')}}</h4>
                        <table class="table table-striped table-hover">
                            <tbody>
                                @foreach($videos as $value)
                                    <tr>
                                        <td>
                                            <label class="w-100">
                                                <input type="checkbox" class="event_videos mx-2" name="videos[]" value="{{$value->id}}" autocomplete="off">
                                                {{$value->title}}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3 mt-3">
                        <button type="button" id="add_doc_vid_to_survey" class="btn btn-blue-gradient" href="">{{__('Add to event')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
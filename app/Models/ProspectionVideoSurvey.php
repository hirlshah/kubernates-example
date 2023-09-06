<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectionVideoSurvey extends Model
{
    use HasFactory;

    protected $table = 'prospection_video_survey';

    protected $guarded = ['id'];
}

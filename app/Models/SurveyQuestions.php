<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'type', 'question', 'survey_id', 'description'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}

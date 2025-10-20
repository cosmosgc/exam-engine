<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'text',
        'type',
        'correct_answer'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function media()
    {
        return $this->hasMany(QuestionMedia::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}

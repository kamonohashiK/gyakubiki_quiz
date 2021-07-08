<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'content', 'user_id'
    ];

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    //現在登録されている全ての問題数を返す
    public static function countQuestions()
    {
        return Question::count();
    }
}

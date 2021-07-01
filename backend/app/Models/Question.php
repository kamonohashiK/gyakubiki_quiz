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
        'content'
    ];

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

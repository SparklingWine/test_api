<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $table="log_entries";
    protected $fillable = ['user_name', 'user_id'];

    public function services()
    {
        return $this->belongsToMany('App\Service')
            ->withTimestamps();
    }
    protected $with = [
        'services'
    ];
}

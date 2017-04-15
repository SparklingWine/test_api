<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    public function log_entries()
    {
        return $this->belongsToMany('App\LogEntry')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scheduleSlot(){
        return $this->belongsTo(ScheduleSlot::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id','from','to'];

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
}

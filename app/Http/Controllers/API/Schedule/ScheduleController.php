<?php

namespace App\Http\Controllers\API\Schedule;

use App\Http\Controllers\API\BaseController;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;

class ScheduleController extends BaseController
{
    /**
     * Get list of available schedules
     */
    public function index()
    {
        $schedules = Schedule::with('slots')->where('status', 1)->get();
        return $this->sendResponse($schedules, 'Scheduels list');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedule = Schedule::with('slots')->find($id);
        if(!$schedule){
            return $this->sendError('Schedule is not found');
        }

        return $this->sendResponse($schedule,'Schedule retrieved successfully');
    }

}

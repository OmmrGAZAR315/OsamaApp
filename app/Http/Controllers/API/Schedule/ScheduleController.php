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
    public function getSchedules()
    {
        $schedules = Schedule::with('slots')->where('status', 1)->get();
        return $this->sendResponse($schedules, 'Scheduels list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        if(!$schedule) {
            return $this->sendError('Valid Schedule ID is required');
        }
        $from = $request->from;
        $to = $request->to;
        if($from >= $to) {
            return $this->sendError('From must be always lower than to');
        }

        $slot = ScheduleSlot::create([
            'schedule_id' => $request->schedule_id,
            'from' => $from,
            'to' => $to
        ]);
        return $this->sendResponse($slot, 'Slot created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

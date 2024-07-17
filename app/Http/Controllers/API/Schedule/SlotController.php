<?php

namespace App\Http\Controllers\API\Schedule;

use Carbon\Carbon;
use App\Models\Schedule;
use Carbon\CarbonPeriod;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class SlotController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slots = ScheduleSlot::with('schedule')->where('status',1)->get();
        return $this->sendResponse($slots,'Slots List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        if(!$schedule){
           return $this->sendError('Valid Schedule ID is required');
        }
        $from = $request->from;
        $to = $request->to;
        if($from >= $to){
            return $this->sendError('From must be always lower than to');
        }

        // Overlapped Slots Validation 
        $getAllEnabledSlots = ScheduleSlot::where('schedule_id',$request->schedule_id)
        ->where('status',1)
        ->get();
        $overlapped_slots = [];
        if(count($getAllEnabledSlots)){
            $today_date = Carbon::today()->format('Y-m-d');
            $period = CarbonPeriod::create("$today_date $request->from", "$today_date $request->to");

            foreach ($getAllEnabledSlots as $slot) {

                $start = Carbon::create("$today_date $slot->from");
                $end = Carbon::create("$today_date $slot->to");
                if($period->overlaps($start, $end)){
                    // it means one slot overlaps with new incoming slot a validation error must be raised 
                    $overlapped_slots [$schedule->day_name] [] = [
                        'slot_id' => $slot->id,
                        'from' => $slot->from,
                        'to' => $slot->to
                    ];
                }
            }
        }
        
        if(count($overlapped_slots)){
            return $this->sendError('Incoming slot overlaps with other slots for the same schedule',$overlapped_slots);
        }

        $slot = ScheduleSlot::create([
            'schedule_id' => $request->schedule_id,
            'from' => $from,
            'to' => $to,
            'status' => $request->status ?? 1
        ]);
        return $this->sendResponse($slot,'Slot created successfully');
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

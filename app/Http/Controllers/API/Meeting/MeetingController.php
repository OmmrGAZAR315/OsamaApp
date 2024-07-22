<?php

namespace App\Http\Controllers\API\Meeting;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Meeting;
use App\Models\ScheduleSlot;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class MeetingController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title =  $request->title;
        if(!$title) {
            $title = 'Meeting-' . str()->random();
        }

        $user_id = $request->user_id;
        $user = User::find($user_id);
        if(!$user){
            return $this->sendError('Unknown user - not found');
        }
        $schedule_slot_id = $request->schedule_slot_id;
        $schedule_slot = ScheduleSlot::with('schedule')->find($schedule_slot_id);
        if(!$schedule_slot){
            return $this->sendError('Invalid schedule slot - not found');
        }

        $schedule_slot_day_name = $schedule_slot->schedule->day_name;
        $meeting_date = $request->meeting_date;
        $meeting_date_day_name = Carbon::parse($meeting_date)->format('l');
        
        if(Carbon::parse($meeting_date)->isPast()){
            return $this->sendError('Meeting date is not accepted as the incoming date is in the past');
        }

        if($meeting_date_day_name != $schedule_slot_day_name){
            return $this->sendError('Meeting date is not accepted as the incoming slot is in different day');
        }
        
        /**
         * Overlap meeting check
         */
        $checkOverlappedMeeting = Meeting::where('meeting_status','pending')
        ->where('schedule_slot_id',$schedule_slot_id)
        ->where('meeting_date',$meeting_date)
        ->first(); 

    
        if($checkOverlappedMeeting){
            return $this->sendError('Another meeting overlap with the incoming request' , [
                'overlapped_meeting_id' => $checkOverlappedMeeting->id,
                'overlapped_meeting_title' => $checkOverlappedMeeting->title,
                'overlapped_meeting_date' => $checkOverlappedMeeting->meeting_date
            ]);
        }


        /**
         * Create new meeting
         */
        $createMeeting = new Meeting;
        $createMeeting->title = $title;
        $createMeeting->user_id = $user_id;
        $createMeeting->schedule_slot_id = $schedule_slot_id;
        $createMeeting->meeting_date = $meeting_date;
        $createMeeting->save();


        /**
         * @todo Notify admins , users with the new created meeting 
         * It should be email and sms notifications 
         */
        return $this->sendResponse(Meeting::find($createMeeting->id),'Meeting created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}

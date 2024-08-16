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
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        $user_id = $request->user_id;
        if($user_id != 'all'){
            $user = User::find($user_id);
            if(!$user){
                return $this->sendError('Unknown user - not found');
            }
            $meetings = Meeting::with(['scheduleSlot'])->where('user_id',$user_id)->paginate($per_page);
            if($meetings){
                return $this->sendResponse($meetings , 'List of the meetings for user ' . $user->name); 
             }
        }else{
            $meetings = Meeting::with(['user','scheduleSlot'])->paginate($per_page);
            if($meetings){
                return $this->sendResponse($meetings , 'List of the meetings for all users'); 
             }
        }
       

        return $this->sendResponse('No meetings found for the incoming user',[]);
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

        // Set schedule slot status to 0 
        $schedule_slot->status = 0;
        $schedule_slot->save();

        /**
         * @todo Notify admins , users with the new created meeting 
         * It should be email and sms notifications 
         */
        return $this->sendResponse(Meeting::with(['user','scheduleSlot'])->find($createMeeting->id),'Meeting created successfully');
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
        // $meeting = Meeting::find($meeting);
        try {
            $meeting->delete();
            return $this->sendResponse('Meeting deleted successfully', 'Delete Meeting');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 'Delete Meeting');
        }
       
    }

    public function approveMeeting(Request $request,$id){
        $meeting = Meeting::find($id);
        if($meeting){
            $user = auth()->user(); 
            if($user->is_admin){
                $meeting->meeting_status = 'awaiting-payment';
                $meeting->save();
                return $this->sendResponse($meeting, 'Meeting approved successfully');
            }else{
                return $this->sendError('User is not admin to approve',[] , 403);
            }
        }

        return $this->sendError('Meeting not found',[] , 404);
    }

    public function inProgressMeeting(Request $request,$id){
        $meeting = Meeting::find($id);
        if($meeting){
                $meeting->meeting_status = 'in-progress';
                $meeting->save();
                return $this->sendResponse($meeting, 'Meeting in progress success request');
        }

        return $this->sendError('Meeting not found',[] , 404);
    }

    public function finishMeeting(Request $request,$id){
        $meeting = Meeting::find($id);
        if($meeting){
                $meeting->meeting_status = 'finished';
                $meeting->save();
                return $this->sendResponse($meeting, 'Meeting finished success request');
        }

        return $this->sendError('Meeting not found',[] , 404);
    }
}

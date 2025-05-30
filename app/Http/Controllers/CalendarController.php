<?php

namespace App\Http\Controllers;

use App\Http\Requests\Calendar\NewDaysOffRequest;
use App\Models\User;
use App\Models\UserOffDay;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('offdays');

        return Inertia::render('Calendar', [
            'user' => $user,
        ]);
    }

    public function store(NewDaysOffRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $batchData = [];
        foreach ($request->newOffDays as $newDate) {
            array_push($batchData, [
                'user_id' => $user->id,
                'date' => $newDate,
                'reason' => $request->reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        UserOffDay::insert($batchData);
        return to_route('calendar');
    }
}

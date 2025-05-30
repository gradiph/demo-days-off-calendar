<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\NewDaysOffRequest;
use App\Models\UserOffDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     *    @OA\Get(
     *       path="/api/v1/calendar",
     *       tags={"Calendar"},
     *       operationId="calendar",
     *       summary="Get Calendar",
     *       description="Get Calendar Data",
     *       security={{"bearerAuth":{}}},
     *       @OA\Header(
     *           header="Accept",
     *           description="Accepted response format",
     *           @OA\Schema(type="string", default="application/json")
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent(
     *               example={
     *                   "id": 1,
     *                   "name": "Test User",
     *                   "email": "test@example.com",
     *                   "email_verified_at": "2025-05-30T04:30:21.000000Z",
     *                   "created_at": "2025-05-30T04:30:21.000000Z",
     *                   "updated_at": "2025-05-30T04:30:21.000000Z",
     *                   "offdays": {
     *                       {
     *                           "id": 1,
     *                           "user_id": 1,
     *                           "date": "2025-05-23",
     *                           "reason": "Beatae omnis optio dolor minus occaecati dolor.",
     *                           "created_at": "2025-05-30T04:30:21.000000Z",
     *                           "updated_at": "2025-05-30T04:30:21.000000Z"
     *                       }
     *                   }
     *               }
     *           ),
     *      ),
     *  )
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('offdays');
        return $user;
    }

    /**
     *    @OA\Post(
     *       path="/api/v1/calendar",
     *       tags={"Calendar"},
     *       operationId="calendar.create",
     *       summary="Create New Days Off",
     *       description="Create New Days Off",
     *       security={{"bearerAuth":{}}},
     *       @OA\Header(
     *           header="Accept",
     *           description="Accepted response format",
     *           @OA\Schema(type="string", default="application/json")
     *       ),
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               required={"email", "password", "token_name"},
     *               @OA\Property(property="email", type="string", default="test@example.com"),
     *               @OA\Property(property="password", type="string", default="password"),
     *               @OA\Property(property="token_name", type="string", default="swagger")
     *           )
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent(
     *               example={
     *                   "id": 1,
     *                   "name": "Test User",
     *                   "email": "test@example.com",
     *                   "email_verified_at": "2025-05-30T04:30:21.000000Z",
     *                   "created_at": "2025-05-30T04:30:21.000000Z",
     *                   "updated_at": "2025-05-30T04:30:21.000000Z",
     *                   "offdays": {
     *                       {
     *                           "id": 1,
     *                           "user_id": 1,
     *                           "date": "2025-05-23",
     *                           "reason": "Beatae omnis optio dolor minus occaecati dolor.",
     *                           "created_at": "2025-05-30T04:30:21.000000Z",
     *                           "updated_at": "2025-05-30T04:30:21.000000Z"
     *                       }
     *                   }
     *               }
     *           ),
     *      ),
     *  )
     */
    public function store(NewDaysOffRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $batchData = [];
        foreach ($request->newOffDays as $new_date) {
            array_push($batchData, [
                'user_id' => $user->id,
                'date' => $new_date,
                'reason' => $request->reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        UserOffDay::insert($batchData);
        return to_route('calendar');
    }
}

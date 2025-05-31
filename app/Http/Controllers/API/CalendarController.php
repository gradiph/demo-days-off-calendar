<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\NewDaysOffRequest;
use App\Http\Resources\DayOffUsersCollection;
use App\Http\Resources\DayOffUsersResource;
use App\Http\Resources\UserOffDaysCollection;
use App\Http\Resources\UserOffDaysResource;
use App\Models\User;
use App\Models\UserOffDay;
use App\Services\OffDayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     *    @OA\Get(
     *       path="/api/v1/calendar",
     *       tags={"Calendar"},
     *       operationId="calendar.self",
     *       summary="Get Calendar for Authenticated User",
     *       description="Get calendar data for authenticated user",
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
     *                  "data": {
     *                      {
     *                          "date": "2025-06-17",
     *                          "reason": "Odio quibusdam suscipit nemo repellendus fugit.",
     *                          "approvedAt": null
     *                      },
     *                      {
     *                          "date": "2025-05-23",
     *                          "reason": "Beatae omnis optio dolor minus occaecati dolor.",
     *                          "approvedAt": "2025-05-31 00:11:09"
     *                      }
     *                   }
     *               }
     *           ),
     *      ),
     *  )
     */
    public function index(OffDayService $offDayService)
    {
        $data = $offDayService->getAllOffDaysOfUser(Auth::id());
        return UserOffDaysResource::collection($data);
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
     *               required={"newOffDays", "userId", "reason"},
     *               @OA\Property(
     *                  property="newOffDays",
     *                  type="array",
     *                  @OA\Items(type="string",default="2025-06-17")
     *               ),
     *               @OA\Property(property="userId", type="number", default="1"),
     *               @OA\Property(property="reason", type="string", default="Lorem ipsum")
     *           )
     *       ),
     *       @OA\Response(
     *           response="201",
     *           description="Ok",
     *           @OA\JsonContent(
     *               example={}
     *           ),
     *      ),
     *  )
     */
    public function store(NewDaysOffRequest $request, OffDayService $offDayService)
    {
        $data = $request->only(['newOffDays', 'userId', 'reason']);
        $offDayService->batchInsert($data);
        return response()->json([], 201);
    }

    /**
     *    @OA\Get(
     *       path="/api/v1/calendar/{user_id}",
     *       tags={"Calendar"},
     *       operationId="calendar.user",
     *       summary="Get Calendar for Specific User",
     *       description="Get calendar data for requested user",
     *       security={{"bearerAuth":{}}},
     *       @OA\Header(
     *           header="Accept",
     *           description="Accepted response format",
     *           @OA\Schema(type="string", default="application/json")
     *       ),
     *       @OA\PathParameter(
     *          name="user_id",
     *          required=true,
     *          description="User ID",
     *          @OA\Schema(type="number", default="1")
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent(
     *               example={
     *                  "data": {
     *                      {
     *                          "date": "2025-06-17",
     *                          "reason": "Odio quibusdam suscipit nemo repellendus fugit.",
     *                          "approvedAt": null
     *                      },
     *                      {
     *                          "date": "2025-05-23",
     *                          "reason": "Beatae omnis optio dolor minus occaecati dolor.",
     *                          "approvedAt": "2025-05-31 00:11:09"
     *                      }
     *                   }
     *               }
     *           ),
     *      ),
     *  )
     */
    public function byUser(User $user, OffDayService $offDayService)
    {
        $data = $offDayService->getAllOffDaysOfUser($user->id);
        return UserOffDaysResource::collection($data);
    }

    /**
     *    @OA\Get(
     *       path="/api/v1/calendar/{date}",
     *       tags={"Calendar"},
     *       operationId="calendar.date",
     *       summary="Get Calendar for Spesific Date",
     *       description="Get calendar data for a date",
     *       security={{"bearerAuth":{}}},
     *       @OA\Header(
     *           header="Accept",
     *           description="Accepted response format",
     *           @OA\Schema(type="string", default="application/json")
     *       ),
     *       @OA\PathParameter(
     *          name="date",
     *          required=true,
     *          description="Date in YYYY-MM-DD format",
     *          @OA\Schema(type="string", default="2025-05-31")
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent(
     *               example={
     *                  "data": {
     *                      {
     *                          "name": "Test User",
     *                          "reason": "Odio quibusdam suscipit nemo repellendus fugit.",
     *                          "approvedAt": null
     *                      },
     *                      {
     *                          "name": "Test User",
     *                          "reason": "Beatae omnis optio dolor minus occaecati dolor.",
     *                          "approvedAt": "2025-05-31 00:11:09"
     *                      }
     *                   }
     *               }
     *           ),
     *      ),
     *  )
     */
    public function byDate(OffDayService $offDayService, $date)
    {
        $data = $offDayService->getAllOffDaysByDate($date);
        return DayOffUsersResource::collection($data);
    }
}

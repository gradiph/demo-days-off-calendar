<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *    @OA\Get(
 *       path="/api/v1/calendar",
 *       tags={"Calendar"},
 *       operationId="calendar",
 *       summary="Get Calendar",
 *       description="Get Calendar Data",
 *       security={{"bearerAuth":{}}},
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
class CalendarController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('offdays');
        return $user;
    }
}

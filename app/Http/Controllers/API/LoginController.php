<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 *    @OA\Post(
 *       path="/api/login",
 *       tags={"Auth"},
 *       operationId="login",
 *       summary="Login",
 *       description="Login",
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
 *           @OA\JsonContent
 *           (example={
 *               "token": "1|5AujuSJSp03eIQNee0ZkyqxiSBBfGIO5RgpAG8Bc227437f5"
 *           }),
 *      ),
 *  )
 */
class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        /** @var User */
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }
        $token = $user->createToken($request->token_name);
        return ['token' => $token->plainTextToken];
    }
}

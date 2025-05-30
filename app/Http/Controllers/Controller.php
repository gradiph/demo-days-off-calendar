<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Demo Days Off Calendar API Documentation",
 *      description="API Documentation for Demo Days Off Calendar using Swagger",
 *      @OA\Contact(
 *          email="gradiph@gmail.com"
 *      )
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication related endpoints"
 * )
 * @OA\Tag(
 *     name="Calendar",
 *     description="Calendar related endpoints"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
abstract class Controller
{
    //
}

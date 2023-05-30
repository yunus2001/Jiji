<?php

namespace App\Http\Controllers;



/**
 * @OA\Info(title="API TICKETS", version="1.0")
 * 
 * @OA\Server(url="http://localhost:8000")
 * 
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

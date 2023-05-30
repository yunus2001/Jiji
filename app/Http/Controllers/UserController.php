<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

     /**
     *  @OA\Post(
     *      path="api/register",
     *      summary="Register a User",
     *      description="As a Seller, I want to signup with my first name, last name, state of residence (where I am selling from), email and password so that I can create an account from the signup page.",
     *      security={{"bearer_token":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Donec sollicitudin molestie malesuada."
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="An error has occurred."
     *      )
     *  )
     */


    /**
     * @OA\Post(
     ** path="/api/register",
     *   tags={"User"},
     *   summary="Register a user",
     *   security={{"bearer_token":{}}},
     *   operationId="register",
     *  @OA\Parameter(
     *      name="first_name",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="last_name",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="email",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
   
     *   @OA\Parameter(
     *      name="password",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *      @OA\Parameter(
     *      name="password_confirmation",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="country",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="state",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="street",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *    @OA\Parameter(
     *      name="local_government",
     *      in="header",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    
    
    //register
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'string', 'email' , Rule::unique('users', 'email')],
            'state' => 'required',
            'password' => 'required|confirmed|min:6',
            'country' => 'required',
            'state' => 'required',
            'local_government' => 'required',
            'street' => 'required',

        ]);

       

        $formFields = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)

        ];


        $user = User::create($formFields);

        $user->address()->create([
            'user_id' => $user->id,
            'country' => $request->country,
            'state' => $request->state,
            'street' => $request->street,
            'local_government' => $request->local_government,
        ]);

        $token = $user->createToken('latyus')->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        

        return response($response, 201);
    }


    

/**
        * @OA\Post(
        * path="/api/login",
        * operationId="login",
        * tags={"User"},
        * summary="User Login",
        * description="As a Seller, I want to login with my email and password so that I can access the platform.",
        * security={{"bearer_token":{}}},
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */


    // login

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' =>'required',
        ]);

        $formFields = [
            'email' =>   $request->email,
            'password' =>   $request->password,
        ];

        $user = User::where('email', $formFields['email'])->first();

        // check password & email

        if(!$user || !Hash::check($formFields['password'], $user->password)){

            return response([
                'message' => 'bad credentals'
            ], 401);
        }

        $token = $user->createToken('latyus')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);


    }


    /**
        * @OA\Post(
        * path="/api/logout",
        * operationId="logout",
        * tags={"User"},
        * summary=" User Logout",
        * summary=" As a User Logout",
        * security={{"bearer_token":{}}},
        * description="you can logout User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="logout Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="logout Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You are Logged out'
        ];
    }
}

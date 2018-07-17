<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\User; 
use App\Role; 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use GuzzleHttp;

use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;
use App\Notifications\UserRegistered;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Symfony\Component\HttpFoundation\Response;
 
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class UserController extends Controller
{

    use SendsPasswordResetEmails;
    protected function ValidationResponse( array $errors)
    {
        return response()->json([
            'error' => $errors,
        ], Response::HTTP_BAD_REQUEST);
    }
     /**
     * Check the login credentials and get the access token
     * @return \Illuminate\Http\Response
     */

     /**
     * @SWG\Post(
     *   path="/api/v1/login",
     *   description= "Check the login credentials and get the access token",
     *   summary="Check the login credentials and get the access token",
     *   operationId="login",
     * @SWG\Parameter(
     *          name="email",
     *          description="User email",
     *          required=true,
     *          type="string",
     *          in="path"
     *   ),
     *  @SWG\Parameter(
     *          name="password",
     *          description="User password",
     *          required=true,
     *          type="string",
     *          in="path"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */

    public function login(Request $request)
    {

        /**
     * Get a validator for an incoming login request.
     *
     * @param  array  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    $valid = validator($request->only( 'email', 'password' ), [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',
    ]);

    if ($valid->fails()) {
       return $this->ValidationResponse($valid->errors()->all());
    }
    
    $user = User::where('email', $request->email)->first();
    
    if(!is_object($user)){
        return $this->ValidationResponse(array('Email is not registered!'));
    }
      
        $client = DB::table('oauth_clients')->where('password_client', 1)->first();
        // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?

        $authParams  = [
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => ''
         ];
         $returnData = $data =  array();
          
        
        $http = new GuzzleHttp\Client;
        try {
            $response = $http->request('post',
            url('/') . '/oauth/token',
                ['form_params' => $authParams]
            );
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return$this->ValidationResponse(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }

        $data['user_id'] = $user->id;
        $data['name'] = $user->name;
        $data['email'] = $user->email; 

        $returnData  = json_decode((string) $response->getBody(), true);

        unset($data['password']);
        $returnData['user'] = $data;
        return response()->json([
            'data' => $returnData,
            'status' => 200
        ]);

    }

    /**
     * Create a new user and get the access token
     * @return \Illuminate\Http\Response
     */

     /**
     * @SWG\Post(
     *   path="/api/v1/register",
     *   description= "Create a new user and get the access token",
     *   summary="Create a new user and get the access token",
     *   operationId="signup",
     *   consumes={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *          name="name",
     *          description="User name",
     *          required=false,
     *          type="string",
     *          in="formData"
     *   ),
     *   @SWG\Parameter(
     *          name="email",
     *          description="User email",
     *          required=true,
     *          type="string",
     *          in="formData"
     *   ),
     *   @SWG\Parameter(
     *          name="password",
     *          description="User password",
     *          required=true,
     *          type="string",
     *          in="formData"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     *
     */

    public function register(Request $request)
    {
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        $valid = validator($request->only('email', 'password' ), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'

        ]);

        if ($valid->fails()) {
            return $this->ValidationResponse($valid->errors()->all());
        }

        $data = request()->only('email','name','password' );
        
        $user = User::create([
            'name' => isset($data['name']) ? $data['name'] : $data['company'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']) 
        ]);
  
        $client = DB::table('oauth_clients')->where('password_client', 1)->first();

        $authParams  = [
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
         ];

        $http = new GuzzleHttp\Client;
        try {
            $response = $http->request('post',
            url('/') . '/oauth/token',
                ['form_params' => $authParams]
            );
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            return $this->ValidationResponse(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }

        $returnData = array();
        $returnData  = json_decode((string) $response->getBody(), true);
        unset($data['password']);
        $returnData['user'] = $data;
        return response()->json([
            'data' => $returnData,
            'status' => 200
        ]);

    }

    /**
     * Send a reset link to the given user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     /**
     * @SWG\Post(
     *   path="/api/v1/forget_password",
     *   description= "Check the email and Send a reset link to the given user ",
     *   summary="Check the email and Send a reset link to the given user  ",
     *   operationId="forget_password",
     * @SWG\Parameter(
     *          name="email",
     *          description="User email",
     *          required=true,
     *          type="string",
     *          in="path"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     *
     */

    public function forgetpass(Request $request)
    {
        $valid = validator($request->only('email'), [
            'email' => 'required|string|email|max:255'
        ]);

        if ($valid->fails()) {
            return $this->ValidationResponse($valid->errors()->all());
        }
        

        $user = User::where('email', $request->email)->first();
        
        if(!is_object($user)){
            return $this->ValidationResponse(array('Email is not registered!'));
        } 
        
        $this->sendResetLinkEmail($request);
        return response()->json([
            'data' => 'We have sent an email with password reset link. Check email',
            'status' => Response::HTTP_OK
        ]);
    }
    
    /**
     * Verify user exist or not
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     /**
     * @SWG\Post(
     *   path="/api/v1/user/verify",
     *   description= "User verification ",
     *   summary="User verification ",
     *   operationId="verifyUser",
     * @SWG\Parameter(
     *          name="email",
     *          description="User email",
     *          required=true,
     *          type="string",
     *          in="path"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     *
     */

    public function verify(Request $request)
    {
        $valid = validator($request->only('email'), [
            'email' => 'required|string|email|max:255'
        ]);

        if ($valid->fails()) {
            return $this->ValidationResponse($valid->errors()->all());
        }
        

        $user = User::where('email', $request->email)->first();
        
        if(!is_object($user)){
            return $this->ValidationResponse(array(false));
        }else{
			return response()->json([
				'data' => true,
				'status' => Response::HTTP_OK
			]);
		} 
         
    }
 


}

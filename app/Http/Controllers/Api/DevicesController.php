<?php

namespace AttendCheck\Http\Controllers\Api;

use Carbon\Carbon;
use AttendCheck\User;
use AttendCheck\Device;
use AttendCheck\ChangeToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use AttendCheck\Http\Controllers\Controller;
use AttendCheck\Repositories\UserRepository;
use AttendCheck\Notifications\DeviceChangeCode;

class DevicesController extends Controller 
{
    /**
     * Various HTTP Error codes.
     * 
     * @var string
     */
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_CONFLICT = 409;
    const HTTP_NOTFOUND = 404;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * Instance of UserRepository
     * 
     * @var \AttendCheck\Repositories\UserRepository
     */
    private $userRepository;

    /**
     * Create a new instance of controller
     * 
     * @param \AttendCheck\Repositories\UserRepository $userRepository
     */
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository; 
    }

    /**
     * Register new device.
     * 
     * @param  Request $request \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validateRegisterRequest($request);

        // If user is not available for registration, just bail out
        // with error request that returns from the method.
        $response = $this->userRepository->checkUserAvailbility($request);
        
        // Error occurred, return the response and bail.
        if (! ($user = $response) instanceof User) {
            return $response;
        }

        $user->password  = bcrypt($request->password);
        $user->email     = $request->email;
        $user->active    = true;
        $user->save();

        $user->device()->save(new Device(['uid' => $this->generateNewDeviceUid()]));

        return response()->json(
            $this->userRepository->getUserDataForMobileApp($user->fresh())->toArray()
        );
    }

    /**
     * Get user data.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|Array
     */
    public function getUserData(Request $request)
    {
        return response()->json(
            $this->userRepository->getUserDataForMobileApp($request->user())->toArray()
        );
    }

    /**
     * WIP
     * Get user attendance record.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|Array
     */
    public function getUserAttendanceRecord(Request $request)
    {
        return response()->json(
            $this->userRepository
            ->getAttendanceDataForMobileApp($request->user())
            ->toArray()
        );
    }

    /**
     * Request the new change device token via email.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function requestChangeDeviceToken(Request $request)
    {
        if (! $request->has('username', 'password')) {
            abort(self::HTTP_UNPROCESSABLE_ENTITY, 'Not enough arguments.');
        }

        // If request is not valid, just bail out with the error 
        // that returns from the method.
        $response = $this->validateChangeDeviceTokenRequest($request);
        
        if (! ($user = $response) instanceof User) {
            return $response;
        }

        $this->sendChangeDeviceTokenEmail($user);

        return response()->json(['status' => 'OK!']);
    }

    /**
     * Validate registration request.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    private function validateRegisterRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            abort(self::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Validate the change device token request.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\AttendCheck\User
     */
    private function validateChangeDeviceTokenRequest(Request $request)
    {
        if (! $user = User::where('username', $request->username)->first()) {
            return response()->json(['error' => 'User not exists'], self::HTTP_NOTFOUND);
        }

        if (! password_verify($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthenticated.'], self::HTTP_UNAUTHORIZED);
        }

        if ($user->device->isEmpty()) {
            return response()->json(['error' => 'User has no device'], self::HTTP_CONFLICT);
        }

        return $user;
    }

    /**
     * Send the change device token email to the user.
     * 
     * @param  \AttendCheck\User   $user
     */
    private function sendChangeDeviceTokenEmail(User $user)
    {
        // If the token is expired, then we will generate the new one.
        $token = $user->token()->first()->expired()
               ? $this->generateChangeDeviceToken($user)
               : $user->token()->first()->token;

        //$user->notify(new DeviceChangeCode($user, $token));
    }

    /**
     * Generate new device UID.
     * 
     * @return string
     */
    private function generateNewDeviceUid()
    {
        do {
            $newUid = bin2hex(openssl_random_pseudo_bytes(ceil(5 / 2)));
        } while (Device::where('uid', $newUid)->exists());

        return $newUid;
    }

    /**
     * Generate the new change device token.
     * 
     * @param  \AttendCheck\User   $user
     * @return string
     */
    private function generateChangeDeviceToken(User $user)
    {
        // Delete the old token
        $user->token()->delete();

        do {
            $newToken = bin2hex(openssl_random_pseudo_bytes(ceil(5 / 2)));
        } while (ChangeToken::where('token', $newToken)->exists());

        $user->token()
             ->save(new ChangeToken([
                'token' => $newToken, 
                'expired' => Carbon::now()->addMinutes(30),
            ]));

        return $newToken;
    }
}

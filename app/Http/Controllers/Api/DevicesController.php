<?php

namespace AttendCheck\Http\Controllers\Api;

use AttendCheck\User;
use AttendCheck\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use AttendCheck\Http\Controllers\Controller;
use AttendCheck\Repositories\UserRepository;

class DevicesController extends Controller 
{
    /**
     * Various HTTP Error codes.
     * 
     * @var string
     */
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
        $response = $this->checkUserAvailbility($request);
        
        if ($response instanceof \Illuminate\Http\Response) {
            return $response;
        }

        $user = $response;

        $user->password  = bcrypt($request->password);
        $user->email     = $request->email;
        $user->active    = true;
        $user->save();

        $user->device()->save(new Device(['uid' => $uid = $this->generateNewDeviceUid()]));

        return response()->json(
            $this->userRepository->getUserDataForMobileApp($user->fresh())->toArray()
        );
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
     * TODO: Move this to user repo.
     * Check for user avaibility to register.
     * Return an instance of \Illuminate\Http\Response if not availible
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\AttendCheck\User
     */
    private function checkUserAvailbility(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()
                    ->json(['error' => 'Email already exists'], self::HTTP_CONFLICT);
        }

        if (! $user = User::where('username', $request->username)->first()) {
            return response()
                    ->json(['error' => 'User not exists'], self::HTTP_NOTFOUND);
        }

        if ($user->password != null) {
            return response()
                    ->json(['error' => 'User already active'], self::HTTP_CONFLICT);
        }

        return $user;
    }

    /**
     * TODO: Move this to device model.
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
}

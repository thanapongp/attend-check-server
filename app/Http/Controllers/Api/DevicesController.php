<?php

namespace AttendCheck\Http\Controllers\Api;

use AttendCheck\User;
use AttendCheck\Device;
use Illuminate\Http\Request;
use AttendCheck\Http\Controllers\Controller;

class DevicesController extends Controller 
{
    const HTTP_CONFLICT = 409;
    const HTTP_NOTFOUND = 404;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * Register new device.
     * 
     * @param  Request $request \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (! $request->has('username') || ! $request->has('password') 
            || ! $request->has('email')) 
        {
            abort(self::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()
                    ->json(['error' => 'Email already exists'], self::HTTP_CONFLICT);
        }

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return response()
                    ->json(['error' => 'User not exists'], self::HTTP_NOTFOUND);
        }

        if ($user->password != null) {
            return response()
                    ->json(['error' => 'User already active'], self::HTTP_CONFLICT);
        }

        //$user->password = bcrypt($request->password);
        //$user->save();

        //$user->device()->save(new Device(['uid' => $uid = $this->generateNewDeviceUid()]));

        //$user = $user->fresh();

        return response()->json([
            'uid' => $this->generateNewDeviceUid(),
            'title' => $user->title,
            'name' => $user->name,
            'lastname' => $user->lastname,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function generateNewDeviceUid()
    {
        do {
            $newUid = bin2hex(openssl_random_pseudo_bytes(ceil(5 / 2)));
        } while (Device::where('uid', $newUid)->exists());

        return $newUid;
    }
}

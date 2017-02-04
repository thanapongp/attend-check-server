<?php

namespace AttendCheck\Http\Controllers\Auth;

use AttendCheck\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use AttendCheck\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register-completed';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $this->create($request->all());

        return redirect($this->redirectTo);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'type' => 'required|in:2,3',
            'faculty' => 'required|in:1',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|min:5|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'title' => $data['title'],
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'type_id' => $data['type'],
            'faculty_id' => $data['faculty'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show registration completed page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationCompletedPage()
    {
        return view('auth.register_completed');
    }
}

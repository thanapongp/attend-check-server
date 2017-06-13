<?php

namespace AttendCheck\Http\Controllers;

use AttendCheck\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where([
            ['id', '!=', current_user()->id],
            ['type_id', '!=', 1],
            ['active', '=', true],
        ])->get();

        return view('dashboard.allusers', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \AttendCheck\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('dashboard.user.show', compact('user'));
    }

    /**
     * Display the specified student.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function showStudent($id)
    {
        $user = User::where('username', $id)->first();
        return view('dashboard.user.showStudent', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \AttendCheck\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \AttendCheck\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \AttendCheck\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back();
    }

    /**
     * Approve the given user.
     * @param  \AttendCheck\User   $user
     * @return \Illuminate\Http\Response
     */
    public function approve(User $user)
    {
        $user->approve();

        return redirect()->back();
    }
}

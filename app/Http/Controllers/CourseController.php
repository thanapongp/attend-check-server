<?php

namespace AttendCheck\Http\Controllers;

use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Repositories\CourseRepository as Repository;

class CourseController extends Controller
{
    protected $requestor;
    protected $repository;

    public function __construct(Requestor $requestor, Repository $repository)
    {
        $this->requestor = $requestor;
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for searching new course.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.course.search');
    }

    /**
     * Show search result page.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showSearchResult(Request $request)
    {
        $response = $this->requestor->searchCourse($request);

        if (property_exists($response, 'COURSE') && $response->COURSE == 'Data Not Found!') {
            return redirect('/dashboard/course/add')
                            ->with('status', 'ไม่พบรายวิชาที่ค้นหา')
                            ->withInput();
        }

        return view('dashboard.course.searchResult', ['course' => $response]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $course = $this->repository->create($request->all());

        $this->repository->findAndEnrollStudent($course);

        return redirect('/dashboard')->with('status', 'เพิ่มรายวิชาสำเร็จ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = '')
    {
        return view('dashboard.course.manage');
    }

    /**
     * Display the specified schedule.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showSchedule()
    {
        return view('dashboard.course.schedule');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace AttendCheck\Http\Controllers;

use Carbon\Carbon;
use AttendCheck\User;
use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Course\Course;
use AttendCheck\Services\CourseExportService as Exporter;
use AttendCheck\Repositories\CourseRepository as Repository;

class CourseController extends Controller
{
    protected $requestor;
    protected $repository;

    public function __construct(
        Requestor $requestor, 
        Repository $repository,
        Exporter $exporter)
    {
        $this->requestor = $requestor;
        $this->repository = $repository;
        $this->exporter = $exporter;
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
    public function show(Course $course)
    {
        return view('dashboard.course.manage', compact('course'));
    }

    /**
     * Display the specified schedule.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showSchedule(Course $course, $schedule)
    {
        $date = Carbon::createFromFormat('d-m-Y-H-i', $schedule);
        $schedule = $course->schedules()->where('start_date', $date)->first();

        return view('dashboard.course.schedule', compact('course', 'schedule'));
    }

    public function showStudentRaw(Course $course, $id)
    {
        $user = User::where('username', $id)->first();

        return view('dashboard.course.showStudentRaw', compact('course', 'user'));
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

    public function export(Course $course)
    {
        return response()->download($this->exporter->export($course));
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

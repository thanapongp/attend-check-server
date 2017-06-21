<?php

namespace AttendCheck\Http\Controllers;

use Carbon\Carbon;
use AttendCheck\User;
use Illuminate\Http\Request;
use AttendCheck\Api\Requestor;
use AttendCheck\Course\Course;
use AttendCheck\Course\Schedule;
use AttendCheck\Repositories\UserRepository;
use AttendCheck\Services\CourseExportService as Exporter;
use AttendCheck\Repositories\CourseRepository as Repository;

class CourseController extends Controller
{
    protected $requestor;
    protected $repository;
    protected $userRepo;
    protected $exporter;

    public function __construct(
        Requestor $requestor, 
        Repository $repository,
        Exporter $exporter, 
        UserRepository $userRepo)
    {
        $this->middleware('role:teacher');
        
        $this->requestor = $requestor;
        $this->repository = $repository;
        $this->userRepo = $userRepo;
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

    public function addSchedule(Course $course, Request $request)
    {
        $date = convertThaiDateToYmd($request->date);

        $room = $request->room;
        $start_date = $date . ' ' . $request->start_time . ':00';
        $end_date = $date . ' ' . $request->end_time . ':00';

        $course->schedules()->save(new Schedule(compact('room', 'start_date', 'end_date')));

        return back()->with('status', 'เพิ่มคาบเรียนสำเร็จ');
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

    public function syncStudents(Course $course)
    {
        $this->repository->findAndEnrollStudent($course);

        return back()->with('status', 'Sync ข้อมูลสำเร็จ');
    }

    public function addStudent(Course $course, Request $request)
    {
        $user = $this->userRepo->createSingleUser($request->all());

        $user->enroll($course);

        return back()->with('status', 'เพิ่มข้อมูลสำเร็จ');
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

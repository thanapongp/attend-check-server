<?php

namespace AttendCheck\Services;

use Jenssegers\Date\Date;
use AttendCheck\Course\Course;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use AttendCheck\Repositories\UserRepository as Repository;
use AttendCheck\Services\AttendanceRecordService as Record;

class CourseExportService
{
    /**
     * Instance of Spreadsheet.
     * 
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    protected $spreadsheet;

    /**
     * Instance of AttendanceRecordService.
     * 
     * @var \AttendCheck\Services\AttendanceRecordService
     */
    protected $record;

    /**
     * Current active worksheet.
     * 
     * @var \PhpOffice\PhpSpreadsheet\Worksheet
     */
    protected $sheet;

    /**
     * Instance of Course that will be exported.
     * 
     * @var \AttendCheck\Course\Course
     */
    protected $course;

    /**
     * Create new instance of CourseExportService.
     * 
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet
     * @param \AttendCheck\Services\AttendanceRecordService $record
     */
    public function __construct(
        Spreadsheet $spreadsheet, 
        Record $record)
    {
        $this->spreadsheet = $spreadsheet;
        $this->record = $record;

        $this->sheet = $spreadsheet->getActiveSheet();

        Date::setLocale('th');
    }

    /**
     * Export course's attendance record into excel file.
     * 
     * @param  \AttendCheck\Course\Course $course
     * @return string Path to the exported file.
     */
    public function export(Course $course)
    {
        $this->course = $course;

        $this->setTheTableHeader();

        $this->writeStudentDataToTable();

        // Return the path to file back to controller to let the controller
        // response the user with file download response.
        return $this->writeFileToDirectory();
    }

    /**
     * Set headers for the table.
     * 
     * @param integer &$i Temporary variable use to track the column position.
     */
    private function setTheTableHeader(&$i = 2)
    {
        $this->sheet->setCellValueByColumnAndRow(0, 1, 'รหัสนักศึกษา');
        $this->sheet->setCellValueByColumnAndRow(1, 1, 'ชื่อสกุล');

        // Because we never know the exact number of each course schedules,
        // we need to have one variable that keep track what column are
        // we currently in. This make the insertion of additional 
        // columns after the schedules possible.
        $this->getAllCourseSchedules()->each(function ($date) use (&$i)  {
            $this->sheet->setCellValueByColumnAndRow($i++, 1, $date);
        });

        $this->sheet->setCellValueByColumnAndRow($i++, 1, 'เข้าเรียน');
        $this->sheet->setCellValueByColumnAndRow($i++, 1, 'สาย');
        $this->sheet->setCellValueByColumnAndRow($i++, 1, 'ขาด');
        $this->sheet->setCellValueByColumnAndRow($i++, 1, '% ขาด');
    }

    private function getAllCourseSchedules()
    {
        return $this->course->schedules->map(function ($schedule) {
            return (new Date($schedule->start_date))->format('j/m/Y');
        });
    }

    private function writeStudentDataToTable(&$i = 2)
    {
        $this->getStudentsData()->each(function ($student) use (&$i) {
            // Because excel formatted data from our AttendanceRecordService
            // is comes in 3 parts, the info, the record and the stat.
            // So we need to take those parts out into seperate
            // variables to make things a bit easier to write.
            $info = $student->take(2);

            $attendances = $student->splice(2, $this->course->schedules->count());

            $stat = $student->take(-4);

            // Because we never know the exact number of each course schedules,
            // we need to have one variable that keep track what column are
            // we currently in. This make the insertion of additional 
            // columns after the schedules possible.
            $y = 0;

            $info->each(function ($item) use (&$y, $i) {
                $this->sheet->setCellValueByColumnAndRow($y++, $i, $item);
            });

            // TODO: Maybe change this. idk.
            $attendances->each(function ($item) use (&$y, $i)  {
                if ($item == 'yes') {
                    $text = 1;
                } elseif ($item = 'late') {
                    $text = 0.5;
                } else {
                    $text = 0;
                }

                $this->sheet->setCellValueByColumnAndRow($y++, $i, $text);
            });

            $stat->each(function ($item) use (&$y, $i)  {
                $this->sheet->setCellValueByColumnAndRow($y++, $i, $item);
            });

            // Go to the next row.
            $i++;
        });
    }

    /**
     * Get each student attendance record in excel formatted data.
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getStudentsData()
    {
        return $this->course->students->map(function ($student) {
            return $this->record->getExcelFormat($this->course, $student);
        });
    }

    /**
     * Write the current spreadsheet to export directory.
     * 
     * @return string Path to the exported file.
     */
    private function writeFileToDirectory()
    {
        if (! is_dir($this->exportDir())) {
            mkdir($this->exportDir(), 0777, true);
        }

        (new Writer($this->spreadsheet))->save($this->getFilename());

        return $this->getFilename();
    }

    /**
     * Get the export directory.
     * 
     * @return string
     */
    private function exportDir()
    {
        return public_path() . '/export/';
    }

    /**
     * Get the filename for the file that will be exported.
     * 
     * @return string
     */
    private function getFilename()
    {
        return $this->exportDir() . $this->course->url() . '.xlsx';
    }
}

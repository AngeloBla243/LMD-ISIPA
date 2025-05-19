<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class StudentAttendanceModel extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';


    static public function CheckAlreadyAttendance($student_id, $class_id, $attendance_date)
    {
        return StudentAttendanceModel::where('student_id', '=', $student_id)->where('class_id', '=', $class_id)->where('attendance_date', '=', $attendance_date)->first();
    }


    static public function getRecord($remove_pagination = 0)
    {
        $return =  StudentAttendanceModel::select('student_attendance.*', 'class.name as class_name', 'class.opt as class_opt', 'student.name as student_name', 'student.last_name as student_last_name', 'createdby.name as created_name')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->join('users as student', 'student.id', '=', 'student_attendance.student_id')
            ->join('users as createdby', 'createdby.id', '=', 'student_attendance.created_by');

        if (!empty(Request::get('student_id'))) {
            $return = $return->where('student_attendance.student_id', '=', Request::get('student_id'));
        }

        if (!empty(Request::get('student_name'))) {
            $return = $return->where('student.name', 'like', '%' . Request::get('student_name') . '%');
        }

        if (!empty(Request::get('student_last_name'))) {
            $return = $return->where('student.last_name', 'like', '%' . Request::get('student_last_name') . '%');
        }

        if (!empty(Request::get('class_id'))) {
            $return = $return->where('student_attendance.class_id', '=', Request::get('class_id'));
        }

        if (!empty(Request::get('start_attendance_date'))) {
            $return = $return->where('student_attendance.attendance_date', '>=', Request::get('start_attendance_date'));
        }

        if (!empty(Request::get('end_attendance_date'))) {
            $return = $return->where('student_attendance.attendance_date', '<=', Request::get('end_attendance_date'));
        }

        if (!empty(Request::get('attendance_type'))) {
            $return = $return->where('student_attendance.attendance_type', '<=', Request::get('attendance_type'));
        }



        $return = $return->orderBy('student_attendance.id', 'asc');

        if (!empty($remove_pagination)) {
            $return = $return->get();
        } else {
            $return = $return->paginate(50);
        }

        return $return;
    }


    static public function getRecordTeacher($class_ids)
    {
        if (!empty($class_ids)) {
            $return =  StudentAttendanceModel::select('student_attendance.*', 'class.name as class_name', 'class.opt as class_opt', 'student.name as student_name', 'student.last_name as student_last_name', 'createdby.name as created_name')
                ->join('class', 'class.id', '=', 'student_attendance.class_id')
                ->join('users as student', 'student.id', '=', 'student_attendance.student_id')
                ->join('users as createdby', 'createdby.id', '=', 'student_attendance.created_by')
                ->whereIn('student_attendance.class_id', $class_ids);

            if (!empty(Request::get('student_id'))) {
                $return = $return->where('student_attendance.student_id', '=', Request::get('student_id'));
            }

            if (!empty(Request::get('student_name'))) {
                $return = $return->where('student.name', 'like', '%' . Request::get('student_name') . '%');
            }

            if (!empty(Request::get('student_last_name'))) {
                $return = $return->where('student.last_name', 'like', '%' . Request::get('student_last_name') . '%');
            }

            if (!empty(Request::get('class_id'))) {
                $return = $return->where('student_attendance.class_id', '=', Request::get('class_id'));
            }


            if (!empty(Request::get('start_attendance_date'))) {
                $return = $return->where('student_attendance.attendance_date', '>=', Request::get('start_attendance_date'));
            }

            if (!empty(Request::get('end_attendance_date'))) {
                $return = $return->where('student_attendance.attendance_date', '<=', Request::get('end_attendance_date'));
            }


            if (!empty(Request::get('attendance_type'))) {
                $return = $return->where('student_attendance.attendance_type', '=', Request::get('attendance_type'));
            }



            $return = $return->orderBy('student_attendance.id', 'asc')
                ->paginate(50);
            return $return;
        } else {
            return "";
        }
    }


    static public function getRecordStudent($student_id)
    {
        $return =  StudentAttendanceModel::select('student_attendance.*', 'class.name as class_name', 'class.opt as class_opt')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', '=', $student_id);

        if (!empty(Request::get('class_id'))) {
            $return = $return->where('student_attendance.class_id', '=', Request::get('class_id'));
        }

        if (!empty(Request::get('attendance_type'))) {
            $return = $return->where('student_attendance.attendance_type', '=', Request::get('attendance_type'));
        }

        if (!empty(Request::get('start_attendance_date'))) {
            $return = $return->where('student_attendance.attendance_date', '>=', Request::get('start_attendance_date'));
        }

        if (!empty(Request::get('end_attendance_date'))) {
            $return = $return->where('student_attendance.attendance_date', '<=', Request::get('end_attendance_date'));
        }

        $return = $return->orderBy('student_attendance.id', 'asc')
            ->paginate(50);
        return $return;
    }

    // modification pour recuperer la presence

    static public function getRecordStudents($student_id, $academic_year_id = null, $filters = [])
    {
        return self::select('student_attendance.*', 'class.name as class_name', 'class.opt as class_opt')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', $student_id)
            ->when($academic_year_id, function ($q) use ($academic_year_id) {
                $q->where('class.academic_year_id', $academic_year_id);
            })
            ->when(!empty($filters['class_id']), function ($q) use ($filters) {
                $q->where('student_attendance.class_id', $filters['class_id']);
            })
            ->when(!empty($filters['attendance_type']), function ($q) use ($filters) {
                $q->where('student_attendance.attendance_type', $filters['attendance_type']);
            })
            ->when(!empty($filters['start_attendance_date']), function ($q) use ($filters) {
                $q->whereDate('student_attendance.attendance_date', '>=', $filters['start_attendance_date']);
            })
            ->when(!empty($filters['end_attendance_date']), function ($q) use ($filters) {
                $q->whereDate('student_attendance.attendance_date', '<=', $filters['end_attendance_date']);
            })
            ->orderBy('student_attendance.attendance_date', 'desc')
            ->paginate(20);
    }




    static public function getRecordStudentCount($student_id)
    {
        $return =  StudentAttendanceModel::select('student_attendance.id')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', '=', $student_id)
            ->count();
        return $return;
    }

    static public function getRecordStudentParentCount($student_ids)
    {
        $return =  StudentAttendanceModel::select('student_attendance.id')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->whereIn('student_attendance.student_id', $student_ids)
            ->count();
        return $return;
    }

    static public function getClassStudent($student_id)
    {
        return StudentAttendanceModel::select('student_attendance.*', 'class.name as class_name', 'class.opt as class_opt')
            ->join('class', 'class.id', '=', 'student_attendance.class_id')
            ->where('student_attendance.student_id', '=', $student_id)
            ->groupBy('student_attendance.class_id')
            ->get();
    }
}

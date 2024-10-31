<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class HomeworkModel extends Model
{
    use HasFactory;

    protected $table = 'homework';

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecord()
    {
        $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->where('homework.is_delete', '=', 0);

        if (!empty(Request::get('class_name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class_name') . '%');
        }

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }


        if (!empty(Request::get('from_homework_date'))) {
            $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
        }

        if (!empty(Request::get('to_homework_date'))) {
            $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
        }


        if (!empty(Request::get('from_submission_date'))) {
            $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
        }

        if (!empty(Request::get('to_submission_date'))) {
            $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
        }


        if (!empty(Request::get('from_created_date'))) {
            $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
        }

        if (!empty(Request::get('to_created_date'))) {
            $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
        }



        $return = $return->orderBy('homework.id', 'asc')
            ->paginate(20);

        return $return;
    }


    // static public function getRecordTeacher($class_ids)
    // {
    //     $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
    //             ->join('users', 'users.id', '=', 'homework.created_by')
    //             ->join('class', 'class.id', '=', 'homework.class_id')
    //             ->join('subject', 'subject.id', '=', 'homework.subject_id')
    //             ->whereIn('homework.class_id', $class_ids)
    //             ->where('homework.is_delete', '=', 0);

    //             if(!empty(Request::get('class_name')))
    //             {
    //                 $return = $return->where('class.name', 'like', '%'.Request::get('class_name').'%');
    //             }

    //             if(!empty(Request::get('subject_name')))
    //             {
    //                 $return = $return->where('subject.name', 'like', '%'.Request::get('subject_name').'%');
    //             }


    //             if(!empty(Request::get('from_homework_date')))
    //             {
    //                 $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
    //             }

    //             if(!empty(Request::get('to_homework_date')))
    //             {
    //                 $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
    //             }


    //             if(!empty(Request::get('from_submission_date')))
    //             {
    //                 $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
    //             }

    //             if(!empty(Request::get('to_submission_date')))
    //             {
    //                 $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
    //             }


    //             if(!empty(Request::get('from_created_date')))
    //             {
    //                 $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
    //             }

    //             if(!empty(Request::get('to_created_date')))
    //             {
    //                 $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
    //             }



    //             $return = $return->orderBy('homework.id', 'asc')
    //             ->paginate(20);

    //     return $return;
    // }

    static public function getRecordTeacher(array $class_ids, array $subject_ids)
    {
        // Vérification si les tableaux ne sont pas vides
        if (empty($class_ids) || empty($subject_ids)) {
            return collect(); // Retourner une collection vide si aucun ID n'est fourni
        }

        return HomeworkModel::select(
            'homework.*',
            'class.name as class_name',
            'subject.name as subject_name',
            'users.name as created_by_name'
        )
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->whereIn('homework.class_id', $class_ids) // Filtrer par les IDs de classe fournis
            ->whereIn('homework.subject_id', $subject_ids) // Filtrer par les IDs de sujet
            ->where('homework.is_delete', '=', 0) // Filtrer les devoirs non supprimés
            ->orderBy('homework.id', 'asc')
            ->paginate(20);
    }


    static public function getRecordStudent($class_id, $student_id)
    {
        $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->where('homework.class_id', '=', $class_id)
            ->where('homework.is_delete', '=', 0)
            ->whereNotIn('homework.id', function ($query) use ($student_id) {
                $query->select('homework_submit.homework_id')
                    ->from('homework_submit')
                    ->where('homework_submit.student_id', '=', $student_id);
            });

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        if (!empty(Request::get('from_homework_date'))) {
            $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
        }

        if (!empty(Request::get('to_homework_date'))) {
            $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
        }


        if (!empty(Request::get('from_submission_date'))) {
            $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
        }

        if (!empty(Request::get('to_submission_date'))) {
            $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
        }

        if (!empty(Request::get('from_created_date'))) {
            $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
        }

        if (!empty(Request::get('to_created_date'))) {
            $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
        }


        $return = $return->orderBy('homework.id', 'asc')
            ->paginate(20);

        return $return;
    }


    static public function getRecordStudentCount($class_id, $student_id)
    {
        $return = HomeworkModel::select('homework.id')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->where('homework.class_id', '=', $class_id)
            ->where('homework.is_delete', '=', 0)
            ->whereNotIn('homework.id', function ($query) use ($student_id) {
                $query->select('homework_submit.homework_id')
                    ->from('homework_submit')
                    ->where('homework_submit.student_id', '=', $student_id);
            });
        $return = $return->orderBy('homework.id', 'asc')
            ->count();

        return $return;
    }





    public function getDocument()
    {
        if (!empty($this->document_file) && file_exists('upload/homework/' . $this->document_file)) {
            return url('upload/homework/' . $this->document_file);
        } else {
            return "";
        }
    }
}

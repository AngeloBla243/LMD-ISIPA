<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cache;
// use Cache;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static public function getSingle($id)
    {
        return self::find($id);
    }

    public function OnlineUer()
    {
        return Cache::has('OnlineUer' . $this->id);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    static public function getStudentsInClass($class_id)
    {
        // On récupère les étudiants de la classe donnée
        $students = User::select('users.*')
            ->join('class', 'class.user.id', '=', 'users.id') // Si vous avez une table de relation entre étudiants et classes
            ->where('class.id', $class_id)  // Assurez-vous que class_user a bien la colonne class_id
            ->where('users.user_type', 3) // Pour récupérer seulement les étudiants (type 3)
            ->get();

        return $students;
    }

    static public function getTotalUser($user_type)
    {
        return self::select('users.id')
            ->where('user_type', '=', $user_type)
            ->where('is_delete', '=', 0)
            ->count();
    }

    static public function getSingleClass($id)
    {
        return self::select('users.*', 'class.amount', 'class.name as class_name')
            ->join('class', 'class.id', 'users.class_id')
            ->where('users.id', '=', $id)
            ->first();
    }

    static public function SearchUser($search)
    {
        $return = self::select('users.*')
            ->where(function ($query) use ($search) {
                $query->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.last_name', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();

        return $return;
    }

    static public function getAdmin()
    {
        $return = self::select('users.*')
            ->where('user_type', '=', 1)
            ->where('is_delete', '=', 0);
        if (!empty(Request::get('name'))) {
            $return = $return->where('name', 'like', '%' . Request::get('name') . '%');
        }

        if (!empty(Request::get('email'))) {
            $return = $return->where('email', 'like', '%' . Request::get('email') . '%');
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('created_at', '=', Request::get('date'));
        }

        $return = $return->orderBy('id', 'asc')
            ->paginate(20);

        return $return;
    }

    static public function getParent($remove_pagination = 0)
    {
        $return = self::select('users.*')
            ->where('user_type', '=', 4)
            ->where('is_delete', '=', 0);

        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }
        if (!empty(Request::get('last_name'))) {
            $return = $return->where('users.last_name', 'like', '%' . Request::get('last_name') . '%');
        }

        if (!empty(Request::get('email'))) {
            $return = $return->where('users.email', 'like', '%' . Request::get('email') . '%');
        }

        if (!empty(Request::get('gender'))) {
            $return = $return->where('users.gender', '=', Request::get('gender'));
        }

        if (!empty(Request::get('mobile_number'))) {
            $return = $return->where('users.mobile_number', 'like', '%' . Request::get('mobile_number') . '%');
        }

        if (!empty(Request::get('address'))) {
            $return = $return->where('users.address', 'like', '%' . Request::get('address') . '%');
        }

        if (!empty(Request::get('occupation'))) {
            $return = $return->where('users.occupation', 'like', '%' . Request::get('occupation') . '%');
        }


        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('users.created_at', '=', Request::get('date'));
        }

        if (!empty(Request::get('status'))) {
            $status = (Request::get('status') == 100) ? 0 : 1;
            $return = $return->whereDate('users.status', '=', $status);
        }


        $return = $return->orderBy('id', 'asc');

        if (!empty($remove_pagination)) {
            $return = $return->get();
        } else {
            $return = $return->paginate(40);
        }


        return $return;
    }


    static public function getCollectFeesStudent()
    {

        $return = self::select('users.*', 'class.name as class_name', 'class.amount')
            ->join('class', 'class.id', '=', 'users.class_id')
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);

        if (!empty(Request::get('class_id'))) {
            $return = $return->where('users.class_id', '=', Request::get('class_id'));
        }

        if (!empty(Request::get('student_id'))) {
            $return = $return->where('users.id', '=', Request::get('student_id'));
        }


        if (!empty(Request::get('first_name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('first_name') . '%');
        }

        if (!empty(Request::get('last_name'))) {
            $return = $return->where('users.last_name', 'like', '%' . Request::get('last_name') . '%');
        }

        $return = $return->orderBy('users.name', 'asc')
            ->paginate(50);

        return $return;
    }



    static public function getStudent($remove_pagination = 0)
    {

        $return = self::select(
            'users.*',
            'parent.name as parent_name',
            'parent.last_name as parent_last_name'
        )
            ->with(['studentClasses.academicYear']) // Charger les classes et l'année académique
            ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.is_delete', '=', 0);

        // $return = self::select(
        //     'users.*',
        //     'class.name as class_name',
        //     'class.opt as class_opt',
        //     'parent.name as parent_name',
        //     'parent.last_name as parent_last_name'
        // )
        //     ->with('studentClasses') // Chargement des relations
        //     ->leftJoin('student_class', 'student_class.student_id', '=', 'users.id')
        //     ->leftJoin('class', 'class.id', '=', 'student_class.class_id')
        //     // ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
        //     ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
        //     ->where('users.user_type', '=', 3)
        //     ->where('users.is_delete', '=', 0)
        //     ->groupBy('users.id');

        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }
        if (!empty(Request::get('last_name'))) {
            $return = $return->where('users.last_name', 'like', '%' . Request::get('last_name') . '%');
        }

        if (!empty(Request::get('departement'))) {
            $return = $return->where('users.departement', '=', Request::get('departement'));
        }

        if (!empty(Request::get('email'))) {
            $return = $return->where('users.email', 'like', '%' . Request::get('email') . '%');
        }

        if (!empty(Request::get('admission_number'))) {
            $return = $return->where('users.admission_number', 'like', '%' . Request::get('admission_number') . '%');
        }

        if (!empty(Request::get('roll_number'))) {
            $return = $return->where('users.roll_number', 'like', '%' . Request::get('roll_number') . '%');
        }

        if (!empty(Request::get('class'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class') . '%');
        }

        if (!empty(Request::get('gender'))) {
            $return = $return->where('users.gender', '=', Request::get('gender'));
        }

        if (!empty(Request::get('caste'))) {
            $return = $return->where('users.caste', 'like', '%' . Request::get('caste') . '%');
        }

        if (!empty(Request::get('religion'))) {
            $return = $return->where('users.religion', 'like', '%' . Request::get('religion') . '%');
        }

        if (!empty(Request::get('mobile_number'))) {
            $return = $return->where('users.mobile_number', 'like', '%' . Request::get('mobile_number') . '%');
        }

        if (!empty(Request::get('blood_group'))) {
            $return = $return->where('users.blood_group', 'like', '%' . Request::get('blood_group') . '%');
        }

        if (!empty(Request::get('admission_date'))) {
            $return = $return->whereDate('users.admission_date', '=', Request::get('admission_date'));
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('users.created_at', '=', Request::get('date'));
        }

        if (!empty(Request::get('status'))) {
            $status = (Request::get('status') == 100) ? 0 : 1;
            $return = $return->where('users.status', '=', $status);
        }


        $return = $return->orderBy('users.id', 'asc');

        if (!empty($remove_pagination)) {
            $return = $return->get();
        } else {
            $return = $return->paginate(40);
        }


        return $return;
    }




    static public function getTeacher($remove_pagination = 0)
    {
        $return = self::select('users.*')
            ->where('users.user_type', '=', 2)
            ->where('users.is_delete', '=', 0);

        if (!empty(Request::get('name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
        }
        if (!empty(Request::get('last_name'))) {
            $return = $return->where('users.last_name', 'like', '%' . Request::get('last_name') . '%');
        }

        if (!empty(Request::get('email'))) {
            $return = $return->where('users.email', 'like', '%' . Request::get('email') . '%');
        }

        if (!empty(Request::get('gender'))) {
            $return = $return->where('users.gender', '=', Request::get('gender'));
        }

        if (!empty(Request::get('mobile_number'))) {
            $return = $return->where('users.mobile_number', 'like', '%' . Request::get('mobile_number') . '%');
        }

        if (!empty(Request::get('marital_status'))) {
            $return = $return->where('users.marital_status', 'like', '%' . Request::get('marital_status') . '%');
        }

        if (!empty(Request::get('address'))) {
            $return = $return->where('users.address', 'like', '%' . Request::get('address') . '%');
        }



        if (!empty(Request::get('admission_date'))) {
            $return = $return->whereDate('users.admission_date', '=', Request::get('admission_date'));
        }

        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('users.created_at', '=', Request::get('date'));
        }

        if (!empty(Request::get('status'))) {
            $status = (Request::get('status') == 100) ? 0 : 1;
            $return = $return->where('users.status', '=', $status);
        }


        $return = $return->orderBy('users.id', 'asc');

        if (!empty($remove_pagination)) {
            $return = $return->get();
        } else {
            $return = $return->paginate(40);
        }


        return $return;
    }


    static public function getUser($user_type)
    {
        return self::select('users.*')
            ->where('user_type', '=', $user_type)
            ->where('is_delete', '=', 0)
            ->get();
    }

    // static public function getStudentClass($class_id)
    // {
    //     return self::select('users.id', 'users.name', 'users.last_name')
    //         ->where('users.user_type', '=', 3)
    //         ->where('users.is_delete', '=', 0)
    //         ->where('users.class_id', '=', $class_id)
    //         ->orderBy('users.id', 'asc')
    //         ->get();
    // }

    // Dans User.php
    static public function getStudentClass($class_id, $academic_year_id = null)
    {
        return self::select(
            'users.id',
            'users.name',
            'users.last_name',
            'student_class.academic_year_id'
        )
            ->join('student_class', 'student_class.student_id', '=', 'users.id')
            ->where('student_class.class_id', $class_id)
            ->when($academic_year_id, function ($query) use ($academic_year_id) {
                $query->where('student_class.academic_year_id', $academic_year_id);
            })
            ->where('users.user_type', 3) // Étudiants uniquement
            ->where('users.is_delete', 0)
            ->groupBy('users.id')
            ->orderBy('users.id', 'asc')
            ->get();
    }



    // static public function getTeacherStudent($teacher_id)
    // {
    //     $return = self::select('users.*', 'class.name as class_name', 'class.opt as class_opt')
    //         ->join('class', 'class.id', '=', 'users.class_id')
    //         ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'class.id')
    //         ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
    //         ->where('assign_class_teacher.status', '=', 0)
    //         ->where('assign_class_teacher.is_delete', '=', 0)
    //         ->where('users.user_type', '=', 3)
    //         ->where('users.is_delete', '=', 0);
    //     $return = $return->orderBy('users.id', 'desc')
    //         ->groupBy('users.id')
    //         ->paginate(20);

    //     return $return;
    // }

    static public function getTeacherStudent($teacher_id, $academic_year_id = null)
    {
        $return = self::select(
            'users.*',
            'class.name as class_name',
            'class.opt as class_opt',
            'academic_years.name as academic_year'
        )
            ->join('student_class', 'student_class.student_id', '=', 'users.id')
            ->join('class', 'class.id', '=', 'student_class.class_id')
            ->join('assign_class_teacher', function ($join) use ($teacher_id, $academic_year_id) {
                $join->on('assign_class_teacher.class_id', '=', 'class.id')
                    ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
                    ->when($academic_year_id, function ($q) use ($academic_year_id) {
                        $q->where('assign_class_teacher.academic_year_id', $academic_year_id);
                    });
            })
            ->join('academic_years', 'academic_years.id', '=', 'student_class.academic_year_id')
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0)
            ->when($academic_year_id, function ($q) use ($academic_year_id) {
                $q->where('student_class.academic_year_id', $academic_year_id);
            })
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc')
            ->paginate(20);

        return $return;
    }



    // static public function getTeacherStudentCount($teacher_id)
    // {
    //     $return = self::select('users.id')
    //         ->join('class', 'class.id', '=', 'users.class_id')
    //         ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'class.id')
    //         ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
    //         ->where('assign_class_teacher.status', '=', 0)
    //         ->where('assign_class_teacher.is_delete', '=', 0)
    //         ->where('users.user_type', '=', 3)
    //         ->where('users.is_delete', '=', 0)
    //         ->orderBy('users.id', 'asc')
    //         ->groupBy('users.id')
    //         ->count();

    //     return $return;
    // }

    static public function getTeacherStudentCount($teacher_id, $academic_year_id = null)
    {
        return self::select('users.id')
            ->join('student_class', 'student_class.student_id', '=', 'users.id')
            ->join('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'student_class.class_id')
            ->where('assign_class_teacher.teacher_id', $teacher_id)
            ->when($academic_year_id, function ($q) use ($academic_year_id) {
                $q->where('student_class.academic_year_id', $academic_year_id);
            })
            ->where('assign_class_teacher.status', '=', 0)
            ->where('assign_class_teacher.is_delete', '=', 0)
            ->count();
    }



    static public function getTeacherClass()
    {
        $return = self::select('users.*')
            ->where('users.user_type', '=', 2)
            ->where('users.is_delete', '=', 0);
        $return = $return->orderBy('users.id', 'asc')
            ->get();

        return $return;
    }




    static public function getSearchStudent()
    {
        if (!empty(Request::get('id')) || !empty(Request::get('name')) || !empty(Request::get('last_name')) || !empty(Request::get('email'))) {
            $return = self::select('users.*', 'class.name as class_name', 'parent.name as parent_name')
                ->join('users as parent', 'parent.id', '=', 'users.parent_id', 'left')
                ->join('class', 'class.id', '=', 'users.class_id', 'left')
                ->where('users.user_type', '=', 3)
                ->where('users.is_delete', '=', 0);

            if (!empty(Request::get('id'))) {
                $return = $return->where('users.id', '=', Request::get('id'));
            }

            if (!empty(Request::get('name'))) {
                $return = $return->where('users.name', 'like', '%' . Request::get('name') . '%');
            }
            if (!empty(Request::get('last_name'))) {
                $return = $return->where('users.last_name', 'like', '%' . Request::get('last_name') . '%');
            }

            if (!empty(Request::get('email'))) {
                $return = $return->where('users.email', 'like', '%' . Request::get('email') . '%');
            }



            $return = $return->orderBy('users.id', 'asc')
                ->limit(50)
                ->get();

            return $return;
        }
    }


    static public function getMyStudent($parent_id)
    {
        $return = self::select('users.*', 'class.name as class_name', 'class.opt as class_opt', 'parent.name as parent_name')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->orderBy('users.id', 'asc')
            ->get();

        return $return;
    }

    public function thesisSubmissions()
    {
        return $this->hasMany(ThesisSubmissio::class, 'student_id');
    }



    static public function getMyStudentCount($parent_id)
    {
        $return = self::select('users.id')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->count();

        return $return;
    }


    static public function getMyStudentIds($parent_id)
    {
        $return = self::select('users.id')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id', 'left')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->orderBy('users.id', 'asc')
            ->get();

        $student_ids = array();
        foreach ($return as $value) {
            $student_ids[] = $value->id;
        }

        return $student_ids;
    }


    static public function getMyStudentClassIds($parent_id)
    {
        $return = self::select('users.class_id')
            ->join('users as parent', 'parent.id', '=', 'users.parent_id')
            ->join('class', 'class.id', '=', 'users.class_id')
            ->where('users.user_type', '=', 3)
            ->where('users.parent_id', '=', $parent_id)
            ->where('users.is_delete', '=', 0)
            ->orderBy('users.id', 'asc')
            ->get();

        $class_ids = array();
        foreach ($return as $value) {
            $class_ids[] = $value->class_id;
        }

        return $class_ids;
    }



    static public function getPaidAmount($student_id, $class_id)
    {
        return StudentAddFeesModel::getPaidAmount($student_id, $class_id);
    }

    static public function getEmailSingle($email)
    {
        return User::where('email', '=', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    {
        return User::where('remember_token', '=', $remember_token)->first();
    }


    public function getProfile()
    {
        if (!empty($this->profile_pic) && file_exists('upload/profile/' . $this->profile_pic)) {
            return url('upload/profile/' . $this->profile_pic);
        } else {
            return '';
        }
    }


    public function getProfileDirect()
    {
        if (!empty($this->profile_pic) && file_exists('upload/profile/' . $this->profile_pic)) {
            return url('upload/profile/' . $this->profile_pic);
        } else {
            return url('upload/profile/user.jpg');
        }
    }



    static public function getAttendance($student_id, $class_id, $attendance_date)
    {
        return StudentAttendanceModel::CheckAlreadyAttendance($student_id, $class_id, $attendance_date);
    }

    public function studentClasses()
    {
        return $this->belongsToMany(
            \App\Models\ClassModel::class,
            'student_class',
            'student_id',
            'class_id'
        )->withPivot('academic_year_id')->with('academicYear');
    }

    public function classes()
    {
        return $this->belongsToMany(
            ClassModel::class,     // Modèle relié
            'student_class',       // Table pivot
            'student_id',          // Clé étrangère de User sur student_class
            'class_id'             // Clé étrangère de Class sur student_class
        )->withPivot('academic_year_id'); // Pour récupérer l'année
    }

    public function getCurrentClass()
    {
        $academicYearId = session('academic_year_id', AcademicYear::where('is_active', 1)->value('id'));

        return $this->belongsToMany(
            ClassModel::class,
            'student_class',
            'student_id',
            'class_id'
        )
            ->wherePivot('academic_year_id', $academicYearId)
            ->first(); // Retourne la première classe de l'année active
    }

    public function studentClass()
    {
        return $this->belongsToMany(
            \App\Models\ClassModel::class,
            'student_class',
            'student_id',
            'class_id'
        )->withPivot('academic_year_id');
    }

    public function studentClasse()
    {
        return $this->hasMany(ClassModel::class, 'student_id');
    }


    //recuperer la class de l'etudiant
    public function studentClas()
    {
        return $this->belongsToMany(
            ClassModel::class,
            'student_class', // Nom de la table pivot
            'student_id',    // Clé étrangère de l'étudiant
            'class_id'       // Clé étrangère de la classe
        )
            ->withPivot('academic_year_id') // Récupère l'année académique
            ->withTimestamps();             // Si la pivot table a des timestamps
    }

    // Pour obtenir la classe actuelle (année académique active)
    public function currentClass()
    {
        $academicYear = AcademicYear::where('is_active', true)->first();

        return $this->belongsToMany(
            ClassModel::class,
            'student_class',
            'student_id',
            'class_id'
        )
            ->wherePivot('academic_year_id', $academicYear->id)
            ->first(); // Retourne directement l'objet Classe
    }
}

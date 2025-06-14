<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AssignClassTeacherController;
use App\Http\Controllers\ClassTimetableController;
use App\Http\Controllers\ExaminationsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CommunicateController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\FeesCollectionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RecoursController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\AdminThesisController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\UeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\MeetingController;













/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'AuthLogin']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('forgot-password', [AuthController::class, 'forgotpassword']);
Route::post('forgot-password', [AuthController::class, 'PostForgotPassword']);
Route::get('reset/{token}', [AuthController::class, 'reset']);
Route::post('reset/{token}', [AuthController::class, 'PostReset']);
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});


// filtre academique
Route::get('/set-academic-year-student', function (\Illuminate\Http\Request $request) {
    $year = App\Models\AcademicYear::findOrFail($request->academic_year_id);
    session(['academic_year_id' => $year->id]);
    return redirect()->back();
})->name('set_academic_year_student')->middleware('auth');

// Route pour les enseignants
Route::get('/set-academic-year-teacher', function (\Illuminate\Http\Request $request) {
    $year = App\Models\AcademicYear::findOrFail($request->academic_year_id);
    session(['academic_year_id' => $year->id]);
    return redirect()->back();
})->name('set_academic_year_teacher')->middleware('auth');







Route::group(['middleware' => 'common'], function () {

    Route::get('chat', [ChatController::class, 'chat']);
    Route::post('submit_message', [ChatController::class, 'submit_message']);
    Route::post('get_chat_windows', [ChatController::class, 'get_chat_windows']);
    Route::post('get_chat_search_user', [ChatController::class, 'get_chat_search_user']);
});





Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('theses', [AdminThesisController::class, 'index'])->name('admin.theses.index');
    Route::get('theses/{id}', [AdminThesisController::class, 'show'])->name('admin.theses.show');
    Route::post('theses/{id}/update', [AdminThesisController::class, 'update'])->name('admin.theses.update');
    Route::delete('/admin/theses/{id}', [AdminThesisController::class, 'destroy'])->name('admin.theses.destroy');
    Route::put('admin/theses/{id}', [AdminThesisController::class, 'update'])->name('admin.theses.update');
    Route::get('admin/theses/{id}/download', [AdminThesisController::class, 'downloadReport'])
        ->name('admin.theses.download');
    Route::get('admin/theses/export', [AdminThesisController::class, 'exportThesesPDF'])
        ->name('admin.theses.export');
});

Route::group(['middleware' => 'student'], function () {
    // Route pour le formulaire de dépôt (étudiant)
    Route::get('student/thesis', [ThesisController::class, 'create'])->name('thesis.submit');
    Route::post('student/thesis', [ThesisController::class, 'store']);

    // Route pour afficher le résultat (étudiant)
    Route::get('student/result/{id}', [ThesisController::class, 'result'])->name('thesis.result');
    Route::get('student/mes-soumissions', [ThesisController::class, 'mySubmissions'])
        ->name('student.submissions');

    // // Route pour télécharger le rapport
    Route::get('student/thesis/download/{id}', [ThesisController::class, 'downloadReport'])->name('downloadReport');
});



Route::prefix('admin/academic-years')->name('admin.academic-years.')->group(function () {
    Route::get('/', [AcademicYearController::class, 'index'])->name('index');
    Route::get('/create', [AcademicYearController::class, 'create'])->name('create');
    Route::post('/', [AcademicYearController::class, 'store'])->name('store');
    Route::get('/{academicYear}/edit', [AcademicYearController::class, 'edit'])->name('edit');
    Route::put('/{academicYear}', [AcademicYearController::class, 'update'])->name('update');
    Route::delete('/{academicYear}', [AcademicYearController::class, 'destroy'])->name('destroy');
    Route::get('/{academicYear}/set-active', [AcademicYearController::class, 'setActive'])->name('set-active');
});

Route::group(['middleware' => 'admin'], function () {

    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{id}', [AdminController::class, 'delete']);

    // teacher

    Route::get('admin/teacher/list', [TeacherController::class, 'list']);
    Route::get('admin/teacher/add', [TeacherController::class, 'add']);
    Route::post('admin/teacher/add', [TeacherController::class, 'insert']);
    Route::get('admin/teacher/edit/{id}', [TeacherController::class, 'edit']);
    Route::post('admin/teacher/edit/{id}', [TeacherController::class, 'update']);
    Route::get('admin/teacher/delete/{id}', [TeacherController::class, 'delete']);
    Route::post('admin/teacher/export_excel', [TeacherController::class, 'export_excel']);


    // student

    Route::get('admin/student/list', [StudentController::class, 'list']);
    Route::get('admin/student/add', [StudentController::class, 'add']);
    Route::post('admin/student/add', [StudentController::class, 'insert']);
    Route::get('admin/student/edit/{id}', [StudentController::class, 'edit']);
    Route::post('admin/student/edit/{id}', [StudentController::class, 'update']);
    Route::get('admin/student/delete/{id}', [StudentController::class, 'delete']);
    Route::post('admin/student/export_excel', [StudentController::class, 'export_excel']);
    Route::get('admin/student/import', [StudentController::class, 'import'])->name('admin.student.import');
    Route::post('admin/student/import', [StudentController::class, 'importSubmit'])->name('admin.student.import.submit');



    // parent

    Route::get('admin/parent/list', [ParentController::class, 'list']);
    Route::get('admin/parent/add', [ParentController::class, 'add']);
    Route::post('admin/parent/add', [ParentController::class, 'insert']);
    Route::get('admin/parent/edit/{id}', [ParentController::class, 'edit']);
    Route::post('admin/parent/edit/{id}', [ParentController::class, 'update']);
    Route::get('admin/parent/delete/{id}', [ParentController::class, 'delete']);
    Route::get('admin/parent/my-student/{id}', [ParentController::class, 'myStudent']);
    Route::get('admin/parent/assign_student_parent/{student_id}/{parent_id}', [ParentController::class, 'AssignStudentParent']);
    Route::get('admin/parent/assign_student_parent_delete/{student_id}', [ParentController::class, 'AssignStudentParentDelete']);
    Route::post('admin/parent/export_excel', [ParentController::class, 'export_excel']);






    // class url

    Route::get('admin/class/list', [ClassController::class, 'list']);
    Route::get('admin/class/add', [ClassController::class, 'add']);
    Route::post('admin/class/add', [ClassController::class, 'insert']);
    Route::get('admin/class/edit/{id}', [ClassController::class, 'edit']);
    Route::post('admin/class/edit/{id}', [ClassController::class, 'update']);
    Route::get('admin/class/delete/{id}', [ClassController::class, 'delete']);


    // subject url

    Route::get('admin/subject/list', [SubjectController::class, 'list']);
    Route::get('admin/subject/add', [SubjectController::class, 'add']);
    Route::post('admin/subject/add', [SubjectController::class, 'insert']);
    Route::get('admin/subject/edit/{id}', [SubjectController::class, 'edit']);
    Route::post('admin/subject/edit/{id}', [SubjectController::class, 'update']);
    Route::get('admin/subject/delete/{id}', [SubjectController::class, 'delete']);


    // assign_subject


    Route::get('admin/assign_subject/list', [ClassSubjectController::class, 'list']);
    Route::get('admin/assign_subject/add', [ClassSubjectController::class, 'add']);
    Route::post('admin/assign_subject/add', [ClassSubjectController::class, 'insert']);
    Route::get('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'edit']);
    Route::post('admin/assign_subject/edit/{id}', [ClassSubjectController::class, 'update']);
    Route::get('admin/assign_subject/delete/{id}', [ClassSubjectController::class, 'delete']);
    Route::get('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'edit_single']);
    Route::post('admin/assign_subject/edit_single/{id}', [ClassSubjectController::class, 'update_single']);

    Route::get('admin/assign_subject/get-classes-subjects', [ClassSubjectController::class, 'getClassesSubjects'])->name('admin.assign_subject.get_classes_subjects');

    Route::get('admin/assign_subject/get-classes/{yearId}', [ClassSubjectController::class, 'getClassesByYear']);
    Route::get('admin/assign_subject/get-subjects/{yearId}', [ClassSubjectController::class, 'getSubjectsByYear']);


    Route::get('admin/class_timetable/list', [ClassTimetableController::class, 'list']);
    Route::post('admin/class_timetable/get_subject', [ClassTimetableController::class, 'get_subject']);
    Route::post('admin/class_timetable/add', [ClassTimetableController::class, 'insert_update']);



    Route::get('/admin/class-timetable/get-classes/{yearId}', [ClassTimetableController::class, 'getClassesByYear']);









    Route::get('admin/account', [UserController::class, 'MyAccount']);
    Route::post('admin/account', [UserController::class, 'UpdateMyAccountAdmin']);

    Route::get('admin/setting', [UserController::class, 'Setting']);
    Route::post('admin/setting', [UserController::class, 'UpdateSetting']);



    Route::get('admin/change_password', [UserController::class, 'change_password']);
    Route::post('admin/change_password', [UserController::class, 'update_change_password']);



    Route::get('admin/assign_class_teacher/list', [AssignClassTeacherController::class, 'list'])->name('admin.assign_class_teacher.list');;
    Route::get('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'add']);
    Route::post('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'insert'])->name('admin.assign_class_teacher.add');
    Route::get('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'edit']);
    Route::post('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'update']);
    Route::get('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'edit_single']);
    Route::post('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'update_single']);
    Route::get('admin/assign_class_teacher/delete/{id}', [AssignClassTeacherController::class, 'delete']);
    Route::get('admin/assign_class_teacher/get-classes/{yearId}', [AssignClassTeacherController::class, 'getClassesByYear']);

    // Route pour assigner un ou plusieurs cours à un enseignant
    // Route::get('admin/assign_class_teacher/assign_subject_subject', [AssignClassTeacherController::class, 'assign_subject']);
    Route::get('admin/assign_class_teacher/assign_subject_subject/{teacher_id}', [AssignClassTeacherController::class, 'assign_subject'])
        ->name('admin.assign_class_teacher.assign_subject_subject');

    // Route::post('admin/assign_class_teacher/assign_subject_subject', [AssignClassTeacherController::class, 'insert_assign_subject']);
    Route::post('admin/assign_class_teacher/assign_subject_subject', [AssignClassTeacherController::class, 'insert_assign_subject'])
        ->name('admin.assign_class_teacher.assign_subject.submit');
    Route::get('admin/assign_class_teacher/assign_subject_subject1/{teacher_id}/{class_id}', [AssignClassTeacherController::class, 'assign_subject1'])->name('admin.assign_class_teacher.assign_subject_subject1');

    // Route pour traiter la soumission du formulaire d'assignation des matières avec AJAX
    // Route::post('admin/assign_class_teacher/assign_subject_subject1', [AssignClassTeacherController::class, 'insert_assign_subject1'])->name('admin.assign_class_teacher.assign_subject_subject1');
    Route::post('/admin/assign_class_teacher/get_subjects', [AssignClassTeacherController::class, 'getSubjectsByTeacher']);
    // Route::post('/admin/assign_class_teacher/get_teacher_details', [AssignClassTeacherController::class, 'getTeacherDetails']);




    Route::get('admin/examinations/exam/list', [ExaminationsController::class, 'exam_list']);
    Route::get('admin/examinations/exam/add', [ExaminationsController::class, 'exam_add']);
    Route::post('admin/examinations/exam/add', [ExaminationsController::class, 'exam_insert']);
    Route::get('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_edit']);
    Route::post('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_update']);
    Route::get('admin/examinations/exam/delete/{id}', [ExaminationsController::class, 'exam_delete']);
    Route::get('admin/my_exam_result/print', [ExaminationsController::class, 'myExamResultPrint']);

    Route::get('admin/result_print/print', [ExaminationsController::class, 'printClassResults']);

    Route::get('admin/examinations/exam_schedule', [ExaminationsController::class, 'exam_schedule']);
    Route::post('admin/examinations/exam_schedule_insert', [ExaminationsController::class, 'exam_schedule_insert']);


    Route::get('admin/examinations/marks_register', [ExaminationsController::class, 'marks_register']);
    Route::post('admin/examinations/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);
    Route::post('admin/examinations/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);



    Route::get('admin/examinations/marks_grade', [ExaminationsController::class, 'marks_grade']);
    Route::get('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_add']);
    Route::post('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_insert']);
    Route::get('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_edit']);
    Route::post('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_update']);
    Route::get('admin/examinations/marks_grade/delete/{id}', [ExaminationsController::class, 'marks_grade_delete']);


    Route::get('admin/attendance/student', [AttendanceController::class, 'AttendanceStudent']);
    Route::post('admin/attendance/student/save', [AttendanceController::class, 'AttendanceStudentSubmit']);
    Route::get('admin/attendance/report', [AttendanceController::class, 'AttendanceReport']);
    Route::post('admin/attendance/report_export_excel', [AttendanceController::class, 'AttendanceReportExportExcel']);


    Route::get('admin/communicate/notice_board', [CommunicateController::class, 'NoticeBoard']);
    Route::get('admin/communicate/notice_board/add', [CommunicateController::class, 'AddNoticeBoard']);
    Route::post('admin/communicate/notice_board/add', [CommunicateController::class, 'InsertNoticeBoard']);

    Route::get('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'EditNoticeBoard']);

    Route::post('admin/communicate/notice_board/edit/{id}', [CommunicateController::class, 'UpdateNoticeBoard']);

    Route::get('admin/communicate/notice_board/delete/{id}', [CommunicateController::class, 'DeleteNoticeBoard']);


    Route::get('admin/communicate/send_email', [CommunicateController::class, 'SendEmail']);
    Route::post('admin/communicate/send_email', [CommunicateController::class, 'SendEmailUser']);

    Route::get('admin/communicate/search_user', [CommunicateController::class, 'SearchUser']);

    // homework

    Route::get('admin/homework/homework', [HomeworkController::class, 'homework']);
    Route::get('admin/homework/homework/add', [HomeworkController::class, 'add']);
    Route::post('admin/homework/homework/add', [HomeworkController::class, 'insert']);

    Route::get('admin/homework/homework/edit/{id}', [HomeworkController::class, 'edit']);
    Route::post('admin/homework/homework/edit/{id}', [HomeworkController::class, 'update']);

    Route::get('admin/homework/homework/delete/{id}', [HomeworkController::class, 'delete']);
    Route::get('admin/homework/homework/submitted/{id}', [HomeworkController::class, 'submitted']);

    Route::get('admin/homework/homework_report', [HomeworkController::class, 'homework_report']);


    Route::get('admin/fees_collection/collect_fees', [FeesCollectionController::class, 'collect_fees']);
    Route::get('admin/fees_collection/collect_fees_report', [FeesCollectionController::class, 'collect_fees_report']);

    Route::post('admin/fees_collection/export_collect_fees_report', [FeesCollectionController::class, 'export_collect_fees_report']);



    Route::get('admin/fees_collection/collect_fees/add_fees/{student_id}', [FeesCollectionController::class, 'collect_fees_add']);

    Route::post('admin/fees_collection/collect_fees/add_fees/{student_id}', [FeesCollectionController::class, 'collect_fees_insert']);

    Route::get('admin/recours/list', [RecoursController::class, 'list']);
    Route::post('admin/recours/toggle-status/{id}', [RecoursController::class, 'toggleStatus'])
        ->name('admin.recours.toggle_status');

    Route::get('admin/recours/mark_register_modal', [ExaminationsController::class, 'markRegisterModal'])
        ->name('admin.recours.mark_register_modal');

    Route::post('admin/recours/update_mark', [ExaminationsController::class, 'updateSingleMark'])
        ->name('admin.recours.update_mark');
    Route::delete('admin/recours/delete/{id}', [RecoursController::class, 'destroy'])
        ->name('admin.recours.delete')
        ->middleware('admin');


    // UE (Unités d'Enseignement)
    Route::get('admin/ue/list', [UeController::class, 'list'])->name('admin.ue.list');
    Route::get('admin/ue/add', [UeController::class, 'add'])->name('admin.ue.add');
    Route::post('admin/ue/add', [UeController::class, 'insert'])->name('admin.ue.insert');
    Route::get('admin/ue/edit/{id}', [UeController::class, 'edit'])->name('admin.ue.edit');
    Route::post('admin/ue/edit/{id}', [UeController::class, 'update'])->name('admin.ue.update');
    Route::get('admin/ue/delete/{id}', [UeController::class, 'delete'])->name('admin.ue.delete');
    Route::post('admin/examinations/exam/{id}/toggle', [ExaminationsController::class, 'toggleExamActive'])
        ->name('admin.examinations.exam.toggle');


    // Semestre
    Route::get('admin/examinations/semestre/list', [SemesterController::class, 'list'])->name('admin.semester.list');
    Route::get('admin/examinations/semestre/create', [SemesterController::class, 'createForm'])->name('admin.semester.create.form');
    Route::post('admin/examinations/semestre/create', [SemesterController::class, 'create'])->name('admin.semester.create');
});


Route::group(['middleware' => 'teacher'], function () {

    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('teacher/change_password', [UserController::class, 'change_password']);
    Route::post('teacher/change_password', [UserController::class, 'update_change_password']);

    Route::get('teacher/account', [UserController::class, 'MyAccount']);
    Route::post('teacher/account', [UserController::class, 'UpdateMyAccount']);


    Route::get('teacher/recours/list', [RecoursController::class, 'listForTeacher']);


    Route::get('teacher/my_student', [StudentController::class, 'MyStudent']);





    Route::get('teacher/my_class_subject', [AssignClassTeacherController::class, 'MyClassSubject']);
    Route::get('teacher/my_class_subject/class_timetable/{class_id}/{subject_id}', [ClassTimetableController::class, 'MyTimetableTeacher']);



    Route::get('teacher/my_exam_timetable', [ExaminationsController::class, 'MyExamTimetableTeacher']);
    Route::get('teacher/my_exam_result/print', [ExaminationsController::class, 'myExamResultPrint']);

    Route::get('teacher/my_calendar', [CalendarController::class, 'MyCalendarTeacher']);


    Route::get('teacher/marks_register', [ExaminationsController::class, 'marks_register_teacher']);
    Route::post('teacher/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);

    Route::post('teacher/submit_all_marks_register', [ExaminationsController::class, 'submit_all_marks_register'])
        ->name('teacher.submit_all_marks_register');
    Route::post('teacher/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);


    Route::get('teacher/attendance/student', [AttendanceController::class, 'AttendanceStudentTeacher']);
    Route::post('teacher/attendance/student/save', [AttendanceController::class, 'AttendanceStudentSubmit']);

    Route::get('teacher/attendance/report', [AttendanceController::class, 'AttendanceReportTeacher']);

    Route::get('teacher/my_notice_board', [CommunicateController::class, 'MyNoticeBoardTeacher']);


    Route::get('teacher/homework/homework', [HomeworkController::class, 'HomeworkTeacher']);
    Route::get('teacher/homework/homework/add', [HomeworkController::class, 'addTeacher']);
    // Route::post('teacher/ajax_get_subject', [HomeworkController::class, 'ajax_get_subject']);
    Route::get('/get-subjects-by-class', [HomeworkController::class, 'getSubjectsByClass'])->name('getSubjectsByClass');
    Route::get('/get-subject-by-class', [HomeworkController::class, 'getSubjectByClass'])->name('getSubjectByClass');



    Route::post('teacher/homework/homework/add', [HomeworkController::class, 'insertTeacher']);
    Route::get('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'editTeacher']);
    Route::post('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'updateTeacher']);

    Route::get('teacher/homework/homework/delete/{id}', [HomeworkController::class, 'delete']);

    Route::get('teacher/homework/homework/submitted/{id}', [HomeworkController::class, 'submittedTeacher']);

    // Teacher Recours Routes
    Route::post('teacher/recours/toggle-status/{id}', [RecoursController::class, 'toggleStatusTeacher'])
        ->name('teacher.recours.toggle_status');

    Route::get('teacher/recours/mark_register_modal', [ExaminationsController::class, 'markRegisterModalTeacher'])
        ->name('teacher.recours.mark_register_modal');
    Route::post('teacher/recours/update_mark', [ExaminationsController::class, 'updateSingleMark'])
        ->name('teacher.recours.update_mark');

    // Pour la liste
    Route::get('teacher/mes-encadres', [TeacherController::class, 'myStudents'])
        ->name('teacher.encadres');

    // Pour le PDF
    Route::get('teacher/encadres/export', [TeacherController::class, 'exportEncadresPDF'])
        ->name('teacher.encadres.export');
    // Pour les enseignants
    Route::get('teacher/meetings', [MeetingController::class, 'list'])->name('teacher.meetings.list');
    Route::get('teacher/meetings/create', [MeetingController::class, 'create'])->name('teacher.meetings.create');
    Route::post('teacher/meetings', [MeetingController::class, 'store'])->name('teacher.meetings.store');

    // Pour les étudiants
    // Route::get('student/meetings', [MeetingController::class, 'studentMeetings'])->name('student.meetings');


    // Pour les étudiants


});


Route::group(['middleware' => 'student'], function () {

    Route::get('student/dashboard', [DashboardController::class, 'dash']);

    Route::get('student/account', [UserController::class, 'MyAccount']);
    Route::post('student/account', [UserController::class, 'UpdateMyAccountStudent']);


    Route::get('student/my_subject', [SubjectController::class, 'MySubject']);
    Route::post('student/my_subject', [SubjectController::class, 'MySubjectRecours']);


    Route::get('student/my_timetable', [ClassTimetableController::class, 'MyTimetable']);

    Route::get('student/my_exam_timetable', [ExaminationsController::class, 'MyExamTimetable']);


    Route::get('student/change_password', [UserController::class, 'change_password']);
    Route::post('student/change_password', [UserController::class, 'update_change_password']);



    Route::get('student/my_calendar', [CalendarController::class, 'MyCalendar']);
    // Route::get('student/my_calendar', [CalendarController::class, 'MyCalendar'])
    //     ->name('student.my_calendar');


    Route::get('student/my_exam_result', [ExaminationsController::class, 'myExamResult']);
    Route::get('student/my_exam_result/print', [ExaminationsController::class, 'myExamResultPrint']);

    Route::get('student/my_exam_result', [ExaminationsController::class, 'myExamResult'])
        ->name('student.exam_result');

    // Pour les étudiants
    Route::post('student/my_subject', [ExaminationsController::class, 'MySubjectRecours'])
        ->name('student.my_subject');



    // Route pour l'impression PDF
    Route::get('student/my_exam_result/print/{academic_year_id}/{student_id}', [ExaminationsController::class, 'generateAnnualResultPrint'])
        ->name('student.year_result.print');





    Route::get('student/my_attendance', [AttendanceController::class, 'MyAttendanceStudent']);


    Route::get('student/my_notice_board', [CommunicateController::class, 'MyNoticeBoardStudent']);


    Route::get('student/my_homework', [HomeworkController::class, 'HomeworkStudent']);
    Route::get('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'SubmitHomework']);
    Route::post('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'SubmitHomeworkInsert']);


    Route::get('student/my_submitted_homework', [HomeworkController::class, 'HomeworkSubmittedStudent']);


    Route::get('student/fees_collection', [FeesCollectionController::class, 'CollectFeesStudent']);

    Route::post('student/fees_collection', [FeesCollectionController::class, 'CollectFeesStudentPayment']);


    Route::get('student/paypal/payment-error', [FeesCollectionController::class, 'PaymentError']);
    Route::get('student/paypal/payment-success', [FeesCollectionController::class, 'PaymentSuccess']);

    Route::get('student/stripe/payment-error', [FeesCollectionController::class, 'PaymentError']);
    Route::get('student/stripe/payment-success', [FeesCollectionController::class, 'PaymentSuccessStripe']);

    Route::get('/student/meetings', [MeetingController::class, 'getClassMeetings'])
        ->middleware('auth:student');
});


Route::group(['middleware' => 'parent'], function () {

    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('parent/account', [UserController::class, 'MyAccount']);
    Route::post('parent/account', [UserController::class, 'UpdateMyAccountParent']);

    Route::get('parent/change_password', [UserController::class, 'change_password']);
    Route::post('parent/change_password', [UserController::class, 'update_change_password']);

    Route::get('parent/my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject']);

    Route::get('parent/my_student/exam_timetable/{student_id}', [ExaminationsController::class, 'ParentMyExamTimetable']);
    Route::get('parent/my_student/exam_result/{student_id}', [ExaminationsController::class, 'ParentMyExamResult']);

    Route::get('parent/my_exam_result/print', [ExaminationsController::class, 'myExamResultPrint']);

    Route::get('parent/my_student/subject/class_timetable/{class_id}/{subject_id}/{student_id}', [ClassTimetableController::class, 'MyTimetableParent']);

    Route::get('parent/my_student/calendar/{student_id}', [CalendarController::class, 'MyCalendarParent']);

    Route::get('parent/my_student/attendance/{student_id}', [AttendanceController::class, 'MyAttendanceParent']);


    Route::get('parent/my_student', [ParentController::class, 'myStudentParent']);

    Route::get('parent/my_student_notice_board', [CommunicateController::class, 'MyStudentNoticeBoardParent']);
    Route::get('parent/my_notice_board', [CommunicateController::class, 'MyNoticeBoardParent']);


    Route::get('parent/my_student/homewrok/{id}', [HomeworkController::class, 'HomeworkStudentParent']);
    Route::get('parent/my_student/submitted_homewrok/{id}', [HomeworkController::class, 'SubmittedHomeworkStudentParent']);


    Route::get('parent/my_student/fees_collection/{student_id}', [FeesCollectionController::class, 'CollectFeesStudentParent']);

    Route::post('parent/my_student/fees_collection/{student_id}', [FeesCollectionController::class, 'CollectFeesStudentPaymentParent']);


    Route::get('parent/paypal/payment-error/{student_id}', [FeesCollectionController::class, 'PaymentErrorParent']);
    Route::get('parent/paypal/payment-success/{student_id}', [FeesCollectionController::class, 'PaymentSuccessParent']);


    Route::get('parent/stripe/payment-error/{student_id}', [FeesCollectionController::class, 'PaymentErrorParent']);

    Route::get('parent/stripe/payment-success/{student_id}', [FeesCollectionController::class, 'PaymentSuccessStripeParent']);
});

@extends('layouts.app')
@section('style')
<style type="text/css">
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }


    .modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
    }

    .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
    }
    .styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}

</style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Marks Register</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Search Marks Register</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Exam</label>
                                            <select class="form-control getClass" name="exam_id" required>
                                                <option value="">Select</option>
                                                @foreach ($getExam as $exam)
                                                    <option {{ (Request::get('exam_id') == $exam->exam_id) ? 'selected' : '' }} value="{{ $exam->exam_id }}">{{ $exam->exam_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Class</label>
                                            <select class="form-control getClass" name="class_id" required>
                                                <option value="">Select</option>
                                                 @foreach ($getClass as $class)
                                                    <option {{ (Request::get('class_id') == $class->class_id) ? 'selected' : '' }} value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <button class="btn btn-primary" type="submit" style="margin-top: 30px;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                                            <a href="{{ url('admin/examinations/marks_register') }}" class="btn btn-success" style="margin-top: 30px;">reset</a>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="customModal" class="modal">
                            <div class="modal-content">
                              <span class="close">&times;</span>
                              <p id="modalMessage"></p>
                            </div>
                        </div>

                        @include('_message')

                        @if (!empty($getSubject) && !empty($getSubject->count()))
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Exam Schedule</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0" style="overflow: auto;">
                                    <table class="table styled-table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>STUDENT NAME</th>
                                                @foreach ($getSubject as $subject)
                                                    <th>
                                                        {{ $subject->subject_name }} <br />
                                                        ({{ $subject->subject_type }}: {{ $subject->passing_mark }} / {{ $subject->full_marks }})
                                                    </th>
                                                @endforeach
                                                <th>ACTION</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && !empty($getStudent->count()))

                                                @foreach ($getStudent as $student)
                                                    <form name="post" class="SubmitForm">
                                                        {{  csrf_field() }}
                                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                        <input type="hidden" name="exam_id" value="{{ Request::get('exam_id') }}">
                                                        <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">
                                                        <tr>
                                                            <td>{{ $student->name }} {{ $student->last_name }}</td>
                                                            @php
                                                                $i = 1;
                                                                $totalStudentMark = 0;
                                                                $totalFullMarks = 0;
                                                                $totalPassinglMarks = 0;
                                                                $pass_fail_vali = 0;
                                                            @endphp
                                                            @foreach ($getSubject as $subject)

                                                                @php
                                                                    $totalFullMarks = $totalFullMarks + ($subject->full_marks * $subject->ponde);
                                                                    $totalPassinglMarks = $totalPassinglMarks + ($subject->passing_mark * $subject->ponde);
                                                                    $totalMark = 0;
                                                                    $totalMarks = 0;
                                                                    $getMark = $subject->getMark($student->id, Request::get('exam_id'), Request::get('class_id'), $subject->subject_id);

                                                                    if (!empty($getMark))
                                                                    {
                                                                        $totalMark = $getMark->class_work + $getMark->home_work + $getMark->test_work + $getMark->exam;
                                                                        $totalMarks = $totalMark * $subject->ponde;
                                                                        $totalPass = $subject->passing_mark * $subject->ponde;
                                                                    }

                                                                    $totalStudentMark = $totalStudentMark+$totalMarks;
                                                                @endphp
                                                                <td>
                                                                    <div style="margin-bottom: 10px;">
                                                                        class Work

                                                                        <input type="hidden" name="mark[{{ $i }}][full_marks]" value="{{ $subject->full_marks  }}">
                                                                        <input type="hidden" name="mark[{{ $i }}][passing_mark]" value="{{ $subject->passing_mark  }}">
                                                                        <input type="hidden" name="mark[{{ $i }}][ponde]" value="{{ $subject->ponde  }}">
                                                                        <input type="hidden" name="mark[{{ $i }}][id]" value="{{ $subject->id }}">
                                                                        <input type="hidden" name="mark[{{ $i }}][subject_id]" value="{{ $subject->subject_id }}">
                                                                        <input type="text" name="mark[{{ $i }}][class_work]" id="class_work_{{ $student->id }}{{ $subject->subject_id }}" style="width: 200px" placeholder="Enter Marks" value="{{ !empty($getMark->class_work) ? $getMark->class_work : '' }}" class="form-control">
                                                                    </div>

                                                                    <div style="margin-bottom: 10px;">
                                                                        Exam
                                                                        <input type="text" name="mark[{{ $i }}][exam]" id="exam_{{ $student->id }}{{ $subject->subject_id }}" style="width: 200px" placeholder="Enter Marks" value="{{ !empty($getMark->exam) ? $getMark->exam : '' }}" class="form-control">
                                                                    </div>

                                                                    <div style="margin-bottom: 10px;">
                                                                        <button type="button" class="btn btn-primary SaveSingleSubject" id="{{ $student->id }}" data-val="{{ $subject->subject_id }}" data-exam="{{ Request::get('exam_id') }}" data-schedule="{{ $subject->id }}" data-class="{{ Request::get('class_id') }}" >Save</button>
                                                                    </div>

                                                                    @if (!empty($getMark))
                                                                        <div style="margin-bottom: 10px;">
                                                                            <b>Crédit Du Cours : </b> {{ $subject->ponde }} <br />
                                                                            @if ($totalMarks >= $totalPass)
                                                                            <b>Result :</b> <span style="color: green; font-weight: bold;">VAL</span>
                                                                            @else
                                                                                <b>Décision :</b> <span style="color: red; font-weight: bold;">NVL</span>
                                                                                @php
                                                                                    $pass_fail_vali = 1;
                                                                                @endphp

                                                                            @endif

                                                                        </div>
                                                                    @endif

                                                                </td>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                            @endforeach
                                                            <td style="min-width: 230px;">
                                                                <button type="submit" class="btn btn-success">Save</button>
                                                                @if (!empty($totalStudentMark))
                                                                    <br >
                                                                    <br >
                                                                    @if ($pass_fail_vali == 0)
                                                                        <b>Result :</b> <span style="color: green; font-weight: bold;">VAL</span>
                                                                    @else
                                                                    <b>Result :</b> <span style="color: red; font-weight: bold;">NVL</span>
                                                                    @endif
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    </form>
                                                @endforeach

                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('script').
<script type="text/javascript">
    $('.SubmitForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ url('teacher/submit_marks_register') }}",
            data : $(this).serialize(),
            dataType : "json",
            success: function(data) {
                data.message.toLowerCase();
                if (data.message.includes('successfully saved')) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else if (data.message.includes('Some Subject mark greater than full mark')) {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });

    $('.SaveSingleSubject').click(function(e) {
        var student_id = $(this).attr('id');
        var subject_id = $(this).attr('data-val');
        var exam_id = $(this).attr('data-exam');
        var class_id = $(this).attr('data-class');
        var id = $(this).attr('data-schedule');
        var class_work = $('#class_work_'+student_id+subject_id).val();
        var exam = $('#exam_'+student_id+subject_id).val();

        $.ajax({
            type: "POST",
            url: "{{ url('teacher/single_submit_marks_register') }}",
            data : {
                '_token': "{{  csrf_token() }}",
                id : id,
                student_id : student_id,
                subject_id : subject_id,
                exam_id : exam_id,
                class_id : class_id,
                class_work : class_work,
                exam : exam,
            },
            dataType : "json",
            success: function(data) {
                data.message.toLowerCase();
                if (data.message.includes('successfully saved')) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(()=> {
                        location.reload();
                    });
                } else if (data.message.includes('greather than full mark')) {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }

        });
    });

</script>

@endsection

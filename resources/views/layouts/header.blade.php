<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>

  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @php
      $AllChatUserCount = App\Models\ChatModel::getAllChatUserCount()
    @endphp
    <!-- Messages Dropdown Menu -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('chat') }}">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">{{ !empty($AllChatUserCount) ? $AllChatUserCount : '' }}</span>
      </a>
    </li>

  </ul>
</nav>
<!-- /.navbar -->


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:;" class="brand-link" style="text-align: center;">
      @if(!empty($getHeaderSetting->getLogo()))
       <img src="{{ $getHeaderSetting->getLogo() }}" style="width: auto;height: 60px;border-radius: 5px;">
      @else
        <span class="brand-text font-weight-light" style="font-weight: bold !important;font-size: 20px;">School</span>
      @endif
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img  style="height: 40px;width: 40px;" src="{{ Auth::user()->getProfileDirect() }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          @if(Auth::user()->user_type == 1)

           <li class="nav-item">
            <a href="{{ url('admin/dashboard') }}"  class="nav-link @if(Request::segment(2) == 'dashboard') active @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/admin/list') }}" class="nav-link @if(Request::segment(2) == 'admin') active @endif">
                <i class="nav-icon fa fa-list-alt"></i>
              <p>
                Admin
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('admin/teacher/list') }}" class="nav-link @if(Request::segment(2) == 'teacher') active @endif">
            <i class="nav-icon fa fa-list-alt"></i>
              <p>
                Teacher
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/student/list') }}" class="nav-link @if(Request::segment(2) == 'student') active @endif">
                <i class="nav-icon fa fa-list-alt"></i>
              <p>
                Student
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/parent/list') }}" class="nav-link @if(Request::segment(2) == 'parent') active @endif">
                <i class="nav-icon fa fa-list-alt"></i>
              <p>
                Parent
              </p>
            </a>
          </li>

         <li class="nav-item  @if(Request::segment(2) == 'class' || Request::segment(2) == 'subject' || Request::segment(2) == 'assign_subject' || Request::segment(2) == 'assign_class_teacher' || Request::segment(2) == 'class_timetable') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'class' || Request::segment(2) == 'subject' || Request::segment(2) == 'assign_subject' || Request::segment(2) == 'assign_class_teacher' || Request::segment(2) == 'class_timetable') active @endif">
              <i class="nav-icon fa-solid fa-calendar-days"></i>
              <p>
                Academics
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/class/list') }}" class="nav-link @if(Request::segment(2) == 'class') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Class</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/subject/list') }}" class="nav-link @if(Request::segment(2) == 'subject') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subject</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/assign_subject/list') }}" class="nav-link @if(Request::segment(2) == 'assign_subject') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Subject</p>
                </a>
              </li>

               <li class="nav-item">
                <a href="{{ url('admin/class_timetable/list') }}" class="nav-link @if(Request::segment(2) == 'class_timetable') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Class Timetable</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ url('admin/assign_class_teacher/list') }}" class="nav-link @if(Request::segment(2) == 'assign_class_teacher') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assign Class Teacher</p>
                </a>
              </li>
            </ul>
          </li>




          <li class="nav-item  @if(Request::segment(2) == 'fees_collection') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'fees_collection') active @endif">
              <i class="nav-icon fa-solid fa-wallet"></i>
              <p>
                Fees Collection
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/fees_collection/collect_fees') }}" class="nav-link @if(Request::segment(3) == 'collect_fees') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collect Fees</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ url('admin/fees_collection/collect_fees_report') }}" class="nav-link @if(Request::segment(3) == 'collect_fees_report') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Collect Fees Report</p>
                </a>
              </li>
            </ul>
        </li>


          <li class="nav-item  @if(Request::segment(2) == 'examinations') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'examinations') active @endif">
              <i class="nav-icon  fa-solid fa-user-pen"></i>
              <p>
                Examinations
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/examinations/exam/list') }}" class="nav-link @if(Request::segment(3) == 'exam') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Exam</p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{ url('admin/examinations/exam_schedule') }}" class="nav-link @if(Request::segment(3) == 'exam_schedule') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Exam Schedule</p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{ url('admin/examinations/marks_register') }}" class="nav-link @if(Request::segment(3) == 'marks_register') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Marks Register</p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{ url('admin/examinations/marks_grade') }}" class="nav-link @if(Request::segment(3) == 'marks_grade') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Marks Grade</p>
                </a>
              </li>



            </ul>
          </li>


         <li class="nav-item  @if(Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'attendance') active @endif">
                <i class="nav-icon fa-solid fa-rectangle-list"></i>
              <p>
                Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{ url('admin/attendance/student') }}" class="nav-link @if(Request::segment(3) == 'student') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Student Attendance</p>
                </a>
              </li>

               <li class="nav-item">
                <a href="{{ url('admin/attendance/report') }}" class="nav-link @if(Request::segment(3) == 'report') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance Report</p>
                </a>
              </li>

            </ul>
        </li>


          <li class="nav-item  @if(Request::segment(2) == 'communicate') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'communicate') active @endif">
              <i class="nav-icon fa-solid fa-envelope"></i>
              <p>
                Communicate
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{ url('admin/communicate/notice_board') }}" class="nav-link @if(Request::segment(3) == 'notice_board') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Notice Board</p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{ url('admin/communicate/send_email') }}" class="nav-link @if(Request::segment(3) == 'send_email') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Send Email</p>
                </a>
              </li>


            </ul>
        </li>


         <li class="nav-item  @if(Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'homework') active @endif">
              <i class="nav-icon fa-solid fa-house"></i>
              <p>
                Homework
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{ url('admin/homework/homework') }}" class="nav-link @if(Request::segment(3) == 'homework') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Homework</p>
                </a>
              </li>

               <li class="nav-item">
                <a href="{{ url('admin/homework/homework_report') }}" class="nav-link @if(Request::segment(3) == 'homework_report') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Homework Report</p>
                </a>
              </li>

            </ul>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/account') }}" class="nav-link @if(Request::segment(2) == 'account') active @endif">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My Account
              </p>
            </a>
          </li>

        <li class="nav-item">
            <a href="{{ url('admin/recours/list') }}" class="nav-link @if(Request::segment(2) == 'recours') active @endif">
                <i class="nav-icon fa-solid fa-folder-open"></i>
              <p>
                Recours
              </p>
            </a>
          </li>



          <li class="nav-item">
            <a href="{{ url('admin/setting') }}" class="nav-link @if(Request::segment(2) == 'setting') active @endif">
                <i class="nav-icon fa-solid fa-gear"></i>
              <p>
                Setting
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('admin/change_password') }}" class="nav-link @if(Request::segment(2) == 'change_password') active @endif">
                <i class="nav-icon fa-solid fa-id-card-clip"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>


          @elseif(Auth::user()->user_type == 2)

             <li class="nav-item">
              <a href="{{ url('teacher/dashboard') }}" class="nav-link @if(Request::segment(2) == 'dashboard') active @endif">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>


              <li class="nav-item">
              <a href="{{ url('teacher/my_student') }}" class="nav-link @if(Request::segment(2) == 'my_student') active @endif">
                <i class="nav-icon fa fa-list-alt"></i>
                <p>
                  My Student
                </p>
              </a>
            </li>


            <li class="nav-item">
              <a href="{{ url('teacher/my_class_subject') }}" class="nav-link @if(Request::segment(2) == 'my_class_subject') active @endif">
                <i class="nav-icon fa-solid fa-book"></i>
                <p>
                  My Class & Subject
                </p>
              </a>
            </li>


          <li class="nav-item">
            <a href="{{ url('teacher/my_exam_timetable') }}" class="nav-link @if(Request::segment(2) == 'my_exam_timetable') active @endif">
                <i class="nav-icon fa-solid fa-calendar-days"></i>
              <p>
                My Exam Timetable
              </p>
            </a>
          </li>



          <li class="nav-item">
            <a href="{{ url('teacher/my_calendar') }}" class="nav-link @if(Request::segment(2) == 'my_calendar') active @endif">
                <i class="nav-icon fa-solid fa-calendar"></i>
              <p>
                My Calendar
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('teacher/marks_register') }}" class="nav-link @if(Request::segment(2) == 'marks_register') active @endif">
             <i class="nav-icon fa-solid fa-pen-to-square"></i>
              <p>
                Marks Register
              </p>
            </a>
          </li>



         <li class="nav-item  @if(Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'attendance') active @endif">
                <i class="nav-icon fa-solid fa-rectangle-list"></i>
              <p>
                Attendance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{ url('teacher/attendance/student') }}" class="nav-link @if(Request::segment(3) == 'student') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Student Attendance</p>
                </a>
              </li>

               <li class="nav-item">
                <a href="{{ url('teacher/attendance/report') }}" class="nav-link @if(Request::segment(3) == 'report') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance Report</p>
                </a>
              </li>

            </ul>
        </li>


         <li class="nav-item  @if(Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link  @if(Request::segment(2) == 'homework') active @endif">
                <i class="nav-icon fa-solid fa-house"></i>
              <p>
                Homework
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{ url('teacher/homework/homework') }}" class="nav-link @if(Request::segment(3) == 'homework') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Homework</p>
                </a>
              </li>
            </ul>
        </li>


          <li class="nav-item">
            <a href="{{ url('teacher/my_notice_board') }}" class="nav-link @if(Request::segment(2) == 'my_notice_board') active @endif">
                <i class="nav-icon fa-solid fa-bell"></i>
              <p>
                My Notice Board
              </p>
            </a>
          </li>


           <li class="nav-item">
            <a href="{{ url('teacher/account') }}" class="nav-link @if(Request::segment(2) == 'account') active @endif">
                <i class="nav-icon fa-solid fa-gear"></i>
              <p>
                My Account
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('teacher/recours/list') }}" class="nav-link @if(Request::segment(2) == 'recurs') active @endif">
                <i class="nav-icon fa-solid fa-folder-open"></i>
              <p>
                 Recours
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('teacher/change_password') }}" class="nav-link @if(Request::segment(2) == 'change_password') active @endif">
                <i class="nav-icon fa-solid fa-id-card-clip"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>


        @elseif(Auth::user()->user_type == 3)

          <li class="nav-item">
              <a href="{{ url('student/dashboard') }}" class="nav-link @if(Request::segment(2) == 'dashboard') active @endif">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

           <li class="nav-item">
            <a href="{{ url('student/fees_collection') }}" class="nav-link @if(Request::segment(2) == 'fees_collection') active @endif">
                <i class="nav-icon fa-solid fa-wallet"></i>
              <p>
                Fees Collection
              </p>
            </a>
          </li>




          <li class="nav-item">
            <a href="{{ url('student/my_calendar') }}" class="nav-link @if(Request::segment(2) == 'my_calendar') active @endif">
                <i class="nav-icon fa-solid fa-calendar-days"></i>
              <p>
                My Calendar
              </p>
            </a>
          </li>



           <li class="nav-item">
            <a href="{{ url('student/my_subject') }}" class="nav-link @if(Request::segment(2) == 'my_subject') active @endif">
                <i class="nav-icon fa-solid fa-book"></i>
              <p>
                My Subject
              </p>
            </a>
          </li>


           <li class="nav-item">
            <a href="{{ url('student/my_timetable') }}" class="nav-link @if(Request::segment(2) == 'my_timetable') active @endif">
              <i class="nav-icon fa-regular fa-clock"></i>
              <p>
                My Timetable
              </p>
            </a>
          </li>



           <li class="nav-item">
            <a href="{{ url('student/my_exam_timetable') }}" class="nav-link @if(Request::segment(2) == 'my_exam_timetable') active @endif">
              <i class="nav-icon fa-solid fa-calendar-plus"></i>
              <p>
                My Exam Timetable
              </p>
            </a>
          </li>


           <li class="nav-item">
            <a href="{{ url('student/my_exam_result') }}" class="nav-link @if(Request::segment(2) == 'my_exam_result') active @endif">
                <i class="nav-icon fa-solid fa-pen-to-square"></i>
              <p>
                My Exam Result
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('student/my_attendance') }}" class="nav-link @if(Request::segment(2) == 'my_attendance') active @endif">
                <i class="nav-icon fa-solid fa-rectangle-list"></i>
              <p>
                My Attendance
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('student/my_notice_board') }}" class="nav-link @if(Request::segment(2) == 'my_notice_board') active @endif">
                <i class="nav-icon fa-solid fa-bell"></i>
              <p>
                My Notice Board
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ url('student/my_homework') }}" class="nav-link @if(Request::segment(2) == 'my_homework') active @endif">
                <i class="nav-icon fa-solid fa-house"></i>
              <p>
                My Homework
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="{{ url('student/my_submitted_homework') }}" class="nav-link @if(Request::segment(2) == 'my_submitted_homework') active @endif">
              <i class="nav-icon fas fa-download"></i>
              <p>
                Submitted Homework
              </p>
            </a>
          </li>


           <li class="nav-item">
            <a href="{{ url('student/account') }}" class="nav-link @if(Request::segment(2) == 'account') active @endif">
                <i class="nav-icon fa-solid fa-gear"></i>
              <p>
                My Account
              </p>
            </a>
          </li>


            <li class="nav-item">
            <a href="{{ url('student/change_password') }}" class="nav-link @if(Request::segment(2) == 'change_password') active @endif">
                <i class="nav-icon fa-solid fa-id-card-clip"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>

          @elseif(Auth::user()->user_type == 4)

          <li class="nav-item">
              <a href="{{ url('parent/dashboard') }}" class="nav-link @if(Request::segment(2) == 'dashboard') active @endif">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>


         <li class="nav-item">
            <a href="{{ url('parent/my_student') }}" class="nav-link @if(Request::segment(2) == 'my_student') active @endif">
                <i class="nav-icon  fa-solid fa-user-pen"></i>
              <p>
                My Student
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a style="min-width: 400px;" href="{{ url('parent/my_student_notice_board') }}" class="nav-link @if(Request::segment(2) == 'my_student_notice_board') active @endif">
              <i class="nav-icon fa-solid fa-street-view"></i>
              <p>
                My Student Notice Board
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ url('parent/my_notice_board') }}" class="nav-link @if(Request::segment(2) == 'my_notice_board') active @endif">
              <i class="nav-icon fa-solid fa-bell"></i>
              <p>
                My Notice Board
              </p>
            </a>
          </li>

         <li class="nav-item">
            <a href="{{ url('parent/account') }}" class="nav-link @if(Request::segment(2) == 'account') active @endif">
                <i class="nav-icon fa-solid fa-gear"></i>
              <p>
                My Account
              </p>
            </a>
          </li>

            <li class="nav-item">
            <a href="{{ url('parent/change_password') }}" class="nav-link @if(Request::segment(2) == 'change_password') active @endif">
                <i class="nav-icon fa-solid fa-id-card-clip"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>

          @endif



          <li class="nav-item">
            <a href="{{ url('logout') }}" class="nav-link">
                <i class="nav-icon fa fa-sign-out"></i>
              <p>
                Logout
              </p>
            </a>
          </li>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

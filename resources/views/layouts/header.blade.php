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
            $AllChatUserCount = App\Models\ChatModel::getAllChatUserCount();
        @endphp
        <!-- Messages Dropdown Menu -->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('chat') }}">
                <i class="far fa-comments"></i>
                <span
                    class="badge badge-danger navbar-badge">{{ !empty($AllChatUserCount) ? $AllChatUserCount : '' }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('logout') }}" class="nav-link">
                <i class="nav-icon fa fa-sign-out-alt"></i>
                <p class="d-inline ml-1">Logout</p>
            </a>
        </li>

    </ul>
</nav>
<!-- /.navbar -->




<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:;" class="brand-link" style="text-align: center;">
        @if (!empty($getHeaderSetting->getLogo()))
            <img src="{{ $getHeaderSetting->getLogo() }}" style="width: auto;height: 60px;border-radius: 5px;">
        @else
            <span class="brand-text font-weight-light"
                style="font-weight: bold !important;font-size: 20px;">School</span>
        @endif
    </a>
    {{-- Dans la barre de navigation --}}
    @if (Auth::check() && Auth::user()->user_type == 3)
        @php
            $allAcademicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
            $currentYear = \App\Models\AcademicYear::find(
                session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id),
            );
        @endphp

        <div class="p-2">
            <form method="GET" action="{{ route('set_academic_year_student') }}" id="academicYearFormSidebar"
                class="d-flex align-items-center" style="gap:8px;">
                <label for="academic_year_id" class="mb-0" style="font-weight:600;">
                    <i class="far fa-calendar-alt text-primary"></i>
                </label>
                <select name="academic_year_id" id="academic_year_id" class="form-select"
                    style="width:190px; background: #f8faff; border: 1.2px solid #007bff; font-weight:600; color:#245aea;"
                    onchange="this.form.submit()">
                    @foreach ($allAcademicYears as $year)
                        <option value="{{ $year->id }}" @if ($currentYear && $year->id == $currentYear->id) selected @endif>
                            {{ $year->name }}{{ $year->is_active ? '' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if ($currentYear && !$currentYear->is_active)
                <div class="alert alert-warning mt-2 py-1 px-2 mb-0 d-flex align-items-center"
                    style="font-size:0.96em;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>Vous n'êtes pas dans l'année académique active</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Filtre année académique pour les enseignants --}}
    @if (Auth::check() && Auth::user()->user_type == 2)
        @php
            $allAcademicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
            $currentYear = \App\Models\AcademicYear::find(
                session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id),
            );
        @endphp

        <div class="p-2">
            <form method="GET" action="{{ route('set_academic_year_teacher') }}" id="academicYearFormSidebarTeacher"
                class="d-flex align-items-center" style="gap:8px;">
                <label for="academic_year_id" class="mb-0" style="font-weight:600;">
                    <i class="far fa-calendar-alt text-primary"></i>
                </label>
                <select name="academic_year_id" id="academic_year_id_teacher" class="form-select"
                    style="width:190px; background: #f8faff; border: 1.2px solid #007bff; font-weight:600; color:#245aea;"
                    onchange="this.form.submit()">
                    @foreach ($allAcademicYears as $year)
                        <option value="{{ $year->id }}" @if ($currentYear && $year->id == $currentYear->id) selected @endif>
                            {{ $year->name }}{{ $year->is_active ? '' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if ($currentYear && !$currentYear->is_active)
                <div class="alert alert-warning mt-2 py-1 px-2 mb-0 d-flex align-items-center"
                    style="font-size:0.96em;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>Année académique non active</span>
                </div>
            @endif
        </div>
    @endif

    @if (Auth::check() && Auth::user()->user_type == 5)
        @php
            $allAcademicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
            $currentYear = \App\Models\AcademicYear::find(
                session('academic_year_id', $allAcademicYears->where('is_active', 1)->first()?->id),
            );
        @endphp

        <div class="p-2">
            <form method="GET" action="{{ route('set_academic_year_teacher') }}" id="academicYearFormSidebarTeacher"
                class="d-flex align-items-center" style="gap:8px;">
                <label for="academic_year_id" class="mb-0" style="font-weight:600;">
                    <i class="far fa-calendar-alt text-primary"></i>
                </label>
                <select name="academic_year_id" id="academic_year_id_teacher" class="form-select"
                    style="width:190px; background: #f8faff; border: 1.2px solid #007bff; font-weight:600; color:#245aea;"
                    onchange="this.form.submit()">
                    @foreach ($allAcademicYears as $year)
                        <option value="{{ $year->id }}" @if ($currentYear && $year->id == $currentYear->id) selected @endif>
                            {{ $year->name }}{{ $year->is_active ? '' : '' }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if ($currentYear && !$currentYear->is_active)
                <div class="alert alert-warning mt-2 py-1 px-2 mb-0 d-flex align-items-center"
                    style="font-size:0.96em;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>Année académique non active</span>
                </div>
            @endif
        </div>
    @endif









    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img style="height: 40px;width: 40px;" src="{{ Auth::user()->getProfileDirect() }}"
                    class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @if (Auth::user()->user_type == 1)
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tableau de bord
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ url('admin/admin/list') }}"
                            class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>
                                Admin
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/departement/list') }}"
                            class="nav-link @if (Request::segment(2) == 'departement') active @endif">

                            <i class="nav-icon fa fa-building"></i>
                            <p>Départements</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/jury/list') }}"
                            class="nav-link @if (Request::segment(2) == 'jury') active @endif">
                            <i class="nav-icon fa fa-gavel"></i>
                            <p>Jury</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/apparitorat/list') }}"
                            class="nav-link @if (Request::segment(2) == 'apparitorat') active @endif">
                            <i class="nav-icon fa fa-user-shield"></i>
                            <p>Apparitorat</p>
                        </a>
                    </li>





                    <li class="nav-item">
                        <a href="{{ url('admin/teacher/list') }}"
                            class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>
                                Professeurs
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/student/list') }}"
                            class="nav-link @if (Request::segment(2) == 'student') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>
                                Étudiants
                            </p>
                        </a>
                    </li> --}}

                    <li class="nav-item @if (Request::segment(2) == 'admin' ||
                            Request::segment(2) == 'departement' ||
                            Request::segment(2) == 'jury' ||
                            Request::segment(2) == 'apparitorat' ||
                            Request::segment(2) == 'teacher' ||
                            Request::segment(2) == 'parent' ||
                            Request::segment(2) == 'student') menu-is-opening menu-open @endif">

                        <a href="#" class="nav-link @if (Request::segment(2) == 'admin' ||
                                Request::segment(2) == 'departement' ||
                                Request::segment(2) == 'jury' ||
                                Request::segment(2) == 'apparitorat' ||
                                Request::segment(2) == 'teacher' ||
                                Request::segment(2) == 'parent' ||
                                Request::segment(2) == 'student') active @endif">
                            <i class="nav-icon fa fa-users-cog"></i>
                            <p>
                                Gestion administrative
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/admin/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Admins</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/departement/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'departement') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Départements</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/jury/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'jury') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jury</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/apparitorat/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'apparitorat') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Apparitorat</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/teacher/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Professeurs</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/student/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'student') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Étudiants</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/parent/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'parent') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Parents
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.theses.settings') }}"
                            class="nav-link @if (Request::segment(2) == 'student') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>
                                Paramètres des thèses (Admin)
                            </p>
                        </a>
                    </li> --}}


                    <li class="nav-item  @if (Request::segment(2) == 'class' ||
                            Request::segment(2) == 'ue' ||
                            Request::segment(2) == 'subject' ||
                            Request::segment(2) == 'assign_subject' ||
                            Request::segment(2) == 'assign_class_teacher' ||
                            Request::segment(2) == 'academic-years' ||
                            Request::segment(2) == 'departement' ||
                            Request::segment(2) == 'theses' ||
                            Request::segment(2) == 'class_timetable') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'class' ||
                                Request::segment(2) == 'subject' ||
                                Request::segment(2) == 'ue' ||
                                Request::segment(2) == 'assign_subject' ||
                                Request::segment(2) == 'assign_class_teacher' ||
                                Request::segment(2) == 'academic-years' ||
                                Request::segment(2) == 'departement' ||
                                Request::segment(2) == 'theses' ||
                                Request::segment(2) == 'class_timetable') active @endif">
                            <i class="nav-icon fa-solid fa-calendar-days"></i>
                            <p>
                                Gestion académique
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('admin.academic-years.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'academic-years') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    Années académiques
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.departementName.list') }}"
                                    class="nav-link @if (Request::segment(2) == 'departement') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    Départements
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('admin/class/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'class') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Classe</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/ue/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'ue') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>UE</p>
                                </a>
                            </li>



                            <li class="nav-item">
                                <a href="{{ url('admin/subject/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'subject') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cours</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.theses.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'theses') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Espace Mémoire</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('admin/assign_subject/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_subject') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Attribuer un Cours</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/class_timetable/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'class_timetable') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Horaire des cours</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/assign_class_teacher/list') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_class_teacher') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Attribuer un enseignant à la classe</p>
                                </a>
                            </li>
                        </ul>
                    </li>




                    <li class="nav-item  @if (Request::segment(2) == 'fees_collection') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'fees_collection') active @endif">
                            <i class="nav-icon fa-solid fa-wallet"></i>
                            <p>
                                Collecte des frais
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/fees_collection/collect_fees') }}"
                                    class="nav-link @if (Request::segment(3) == 'collect_fees') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Percevoir les frais</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/fees_collection/collect_fees_report') }}"
                                    class="nav-link @if (Request::segment(3) == 'collect_fees_report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rapport de collecte des frais</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('admin.fee_types.index') }}"
                                    class="nav-link {{ Request::is('admin/fee-types*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-money-check-alt"></i>
                                    <p>
                                        Types de frais & périodes
                                    </p>
                                </a>
                            </li>



                        </ul>
                    </li>


                    <li class="nav-item  @if (Request::segment(2) == 'examinations') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'examinations') active @endif">
                            <i class="nav-icon  fa-solid fa-user-pen"></i>
                            <p>
                                Gestion de Session
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('admin.semester.list') }}"
                                    class="nav-link @if (Request::segment(3) == 'semestre') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Semestre</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/exam/list') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Session</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/exam_schedule') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam_schedule') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Calendrier des sessions</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('admin/examinations/marks_register') }}"
                                    class="nav-link @if (Request::segment(3) == 'marks_register') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Fiche de Cotation</p>
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/features') }}"
                            class="nav-link @if (Request::segment(2) == 'features') active @endif">
                            <i class="nav-icon fas fa-toggle-on"></i>
                            <p>Gestion des fonctionnalités</p>
                        </a>
                    </li>

                    <li class="nav-item  @if (Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'attendance') active @endif">
                            <i class="nav-icon fa-solid fa-rectangle-list"></i>
                            <p>
                                Gestion de Présence
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/student') }}"
                                    class="nav-link @if (Request::segment(3) == 'student') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Présence des étudiants</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/report') }}"
                                    class="nav-link @if (Request::segment(3) == 'report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rapport de présence</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item  @if (Request::segment(2) == 'communicate') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'communicate') active @endif">
                            <i class="nav-icon fa-solid fa-envelope"></i>
                            <p>
                                Communiquer
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/communicate/notice_board') }}"
                                    class="nav-link @if (Request::segment(3) == 'notice_board') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tableau d'affichage</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ url('admin/communicate/send_email') }}"
                                    class="nav-link @if (Request::segment(3) == 'send_email') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Envoyer un e-mail</p>
                                </a>
                            </li>


                        </ul>
                    </li>


                    <li class="nav-item  @if (Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'homework') active @endif">
                            <i class="nav-icon fa-solid fa-house"></i>
                            <p>
                                Devoirs
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('admin/homework/homework') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Devoirs</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('admin/homework/homework_report') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework_report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rapport de devoirs</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                My Account
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/recours/list') }}"
                            class="nav-link @if (Request::segment(2) == 'recours') active @endif">
                            <i class="nav-icon fa-solid fa-folder-open"></i>
                            <p>
                                Recours
                            </p>
                        </a>
                    </li>



                    <li class="nav-item">
                        <a href="{{ url('admin/setting') }}"
                            class="nav-link @if (Request::segment(2) == 'setting') active @endif">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>
                                Paramètre
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('admin/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 2)
                    <li class="nav-item">
                        <a href="{{ url('teacher/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tableau de bord
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('teacher/my_student') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon fa fa-list-alt"></i>
                            <p>
                                Mes Etudiants
                            </p>
                        </a>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'my_calendar' ||
                            Request::segment(2) == 'my_exam_timetable' ||
                            Request::segment(2) == 'my_class_subject') menu-is-opening menu-open @endif">

                        <a href="#" class="nav-link @if (Request::segment(2) == 'my_calendar' ||
                                Request::segment(2) == 'my_exam_timetable' ||
                                Request::segment(2) == 'my_class_subject') active @endif">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                Mon Organisation
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('teacher/my_calendar') }}"
                                    class="nav-link @if (Request::segment(2) == 'my_calendar') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mon calendrier</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('teacher/my_exam_timetable') }}"
                                    class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mon emploi du temps</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('teacher/my_class_subject') }}"
                                    class="nav-link @if (Request::segment(2) == 'my_class_subject') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mes classes et mes cours</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('teacher/exams') }}"
                            class="nav-link @if (Request::segment(2) == 'exams' && Request::segment(1) == 'teacher') active @endif">
                            <i class="nav-icon fa fa-question-circle"></i>
                            <p>
                                Mes Questionnaires
                            </p>
                        </a>
                    </li>



                    @if (\App\Models\FeatureToggle::isFeatureActive('teacher_marks_register'))
                        <li class="nav-item">
                            <a href="{{ url('teacher/marks_register') }}"
                                class="nav-link @if (Request::segment(2) == 'marks_register') active @endif">
                                <i class="nav-icon fa-solid fa-pen-to-square"></i>
                                <p>Fiche de Cotation</p>
                            </a>
                        </li>
                    @endif


                    {{-- Dans resources/views/layouts/sidebar-teacher.blade.php --}}
                    <li class="nav-item">
                        <a href="{{ route('teacher.encadres') }}"
                            class="nav-link @if (Request::is('teacher/encadres*')) active @endif">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Mes encadrés</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.meetings.create') }}"
                            class="nav-link @if (Request::is('teacher/meetings/create*')) active @endif">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>visioconférence</p>
                        </a>
                    </li>


                    @if (\App\Models\FeatureToggle::isFeatureActive('teacher_marks_register'))
                        <li class="nav-item">
                            <a href="{{ url('teacher/recours/list') }}"
                                class="nav-link @if (Request::segment(2) == 'recurs') active @endif">
                                <i class="nav-icon fa-solid fa-folder-open"></i>
                                <p>
                                    Recours
                                </p>
                            </a>
                        </li>
                    @endif



                    <li class="nav-item  @if (Request::segment(2) == 'attendance') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'attendance') active @endif">
                            <i class="nav-icon fa-solid fa-rectangle-list"></i>
                            <p>
                                Gestion de Présence
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('teacher/attendance/student') }}"
                                    class="nav-link @if (Request::segment(3) == 'student') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Présence des étudiants</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('teacher/attendance/report') }}"
                                    class="nav-link @if (Request::segment(3) == 'report') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rapport de présence</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item  @if (Request::segment(2) == 'homework') menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link  @if (Request::segment(2) == 'homework') active @endif">
                            <i class="nav-icon fa-solid fa-house"></i>
                            <p>
                                Devoirs
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('teacher/homework/homework') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Devoirs</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('teacher/my_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_notice_board') active @endif">
                            <i class="nav-icon fa-solid fa-bell"></i>
                            <p>
                                Valves
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('teacher/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>
                                Mon compte
                            </p>
                        </a>
                    </li>




                    <li class="nav-item">
                        <a href="{{ url('teacher/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 3)
                    <li class="nav-item">
                        <a href="{{ url('student/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tableau de bord
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/my_notice_board') }}"
                            class="nav-link {{ Request::is('student/my_notice_board*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-bell"></i>
                            <p>
                                Valves
                                @if (!empty($unread_notices) && $unread_notices > 0)
                                    <span class="badge bg-danger ms-2">{{ $unread_notices }}</span>
                                @endif
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/fees_collection') }}"
                            class="nav-link @if (Request::segment(2) == 'fees_collection') active @endif">
                            <i class="nav-icon fa-solid fa-wallet"></i>
                            <p>
                                Collecte des frais
                            </p>
                        </a>
                    </li>

                    {{-- Lien Student Mes Soumissions --}}
                    @if (\App\Models\FeatureToggle::isFeatureActive('student_submissions'))
                        <li class="nav-item @if (Request::segment(2) == 'thesis' || Request::is('student/submissions*')) menu-is-opening menu-open @endif">

                            <a href="#" class="nav-link @if (Request::segment(2) == 'thesis' || Request::is('student/submissions*')) active @endif">
                                <i class="nav-icon fas fa-upload"></i>
                                <p>
                                    Mes Soumissions
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ url('student/thesis') }}"
                                        class="nav-link @if (Request::segment(2) == 'thesis') active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Soumettre mon mémoire</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('student.submissions') }}"
                                        class="nav-link {{ Request::is('student/submissions*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Mes Soumissions
                                            @if (isset($newSubmissionCount) && $newSubmissionCount > 0)
                                                <span class="badge bg-danger ms-2">{{ $newSubmissionCount }}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif




                    <li class="nav-item">
                        <a href="{{ url('student/my_calendar') }}"
                            class="nav-link @if (Request::segment(2) == 'my_calendar') active @endif">
                            <i class="nav-icon fa-solid fa-calendar-days"></i>
                            <p>
                                Mon calendrier
                            </p>
                        </a>
                    </li>



                    <li class="nav-item">
                        <a href="{{ url('student/my_subject') }}"
                            class="nav-link @if (Request::segment(2) == 'my_subject') active @endif">
                            <i class="nav-icon fa-solid fa-book"></i>
                            <p>
                                Mes Cours
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('student/my_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_timetable') active @endif">
                            <i class="nav-icon fa-regular fa-clock"></i>
                            <p>
                                Mon emploi du temps
                            </p>
                        </a>
                    </li>



                    <li class="nav-item">
                        <a href="{{ url('student/my_exam_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                            <i class="nav-icon fa-solid fa-calendar-plus"></i>
                            <p>
                                Mon emploi du temps d'examen
                            </p>
                        </a>
                    </li>


                    {{-- Lien Mes Résultats (Student) --}}
                    @if (\App\Models\FeatureToggle::isFeatureActive('student_exam_results'))
                        <li class="nav-item">
                            <a href="{{ url('student/my_exam_result') }}"
                                class="nav-link @if (Request::segment(2) == 'my_exam_result') active @endif">
                                <i class="nav-icon fa-solid fa-pen-to-square"></i>
                                <p>Mes Résultats</p>
                            </a>
                        </li>
                    @endif


                    <li class="nav-item">
                        <a href="{{ url('student/my_attendance') }}"
                            class="nav-link @if (Request::segment(2) == 'my_attendance') active @endif">
                            <i class="nav-icon fa-solid fa-rectangle-list"></i>
                            <p>
                                Ma présence
                            </p>
                        </a>
                    </li>

                    <li class="nav-item @if (Request::segment(2) == 'exams' ||
                            Request::segment(2) == 'my_homework' ||
                            Request::segment(2) == 'my_submitted_homework') menu-is-opening menu-open @endif">

                        <a href="#" class="nav-link @if (Request::segment(2) == 'exams' ||
                                Request::segment(2) == 'my_homework' ||
                                Request::segment(2) == 'my_submitted_homework') active @endif">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                Mes Cours & Examens
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('student/exams') }}"
                                    class="nav-link @if (Request::segment(2) == 'exams') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Examens en Ligne</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('student/my_homework') }}"
                                    class="nav-link @if (Request::segment(2) == 'my_homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mes Devoirs</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('student/my_submitted_homework') }}"
                                    class="nav-link @if (Request::segment(2) == 'my_submitted_homework') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Devoirs soumis</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('student/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>
                                Mon compte
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('student/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 4)
                    <li class="nav-item">
                        <a href="{{ url('parent/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Tableau de bord
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('parent/my_student') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon  fa-solid fa-user-pen"></i>
                            <p>
                                Mes Etudiants
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a style="min-width: 400px;" href="{{ url('parent/my_student_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student_notice_board') active @endif">
                            <i class="nav-icon fa-solid fa-street-view"></i>
                            <p>
                                tableau d'affichage des étudiants
                            </p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ url('parent/my_notice_board') }}"
                            class="nav-link @if (Request::segment(2) == 'my_notice_board') active @endif">
                            <i class="nav-icon fa-solid fa-bell"></i>
                            <p>
                                Mon tableau d'affichage
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/account') }}"
                            class="nav-link @if (Request::segment(2) == 'account') active @endif">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>
                                Mon compte
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('parent/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 5)
                    <li class="nav-item">
                        <a href="{{ url('departement/dashboard') }}"
                            class="nav-link @if (Request::segment(1) == 'departement' && Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fa fa-building"></i>
                            <p>Tableau de bord Département</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('departement/student/list') }}"
                            class="nav-link @if (Request::segment(1) == 'departement' && Request::segment(2) == 'student') active @endif">
                            <i class="nav-icon fa fa-list"></i>
                            <p>Liste des étudiants</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('departement/assign_subject/list') }}"
                            class="nav-link @if (Request::segment(1) == 'departement' && Request::segment(2) == 'assign_subject') active @endif">
                            <i class="nav-icon fa fa-book"></i>
                            <p>Assignation des matières</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('departement.assign_teacher.list') }}"
                            class="nav-link @if (request()->is('departement/assign_teacher*')) active @endif">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Assignation Professeurs</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('departement.class_timetable.list') }}"
                            class="nav-link @if (request()->is('departement/class_timetable*')) active @endif">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Emploi du Temps</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('departement.exam_schedule') }}"
                            class="nav-link @if (request()->is('departement/exam_schedule*')) active @endif">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Planning Examens</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('departement.marks_register') }}"
                            class="nav-link @if (request()->is('departement/marks_register*')) active @endif">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Fiche de Cotation</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('departement.recours.list') }}"
                            class="nav-link @if (request()->is('departement/recours*')) active @endif">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Gestion des Recours</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('departement/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 6)
                    <li class="nav-item">
                        <a href="{{ url('jury/dashboard') }}"
                            class="nav-link @if (Request::segment(1) == 'jury' && Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fa fa-gavel"></i>
                            <p>Tableau de bord Jury</p>
                        </a>
                    </li>

                    {{-- Lien Jury Fiche de Cotation --}}
                    @if (\App\Models\FeatureToggle::isFeatureActive('jury_marks_register'))
                        <li class="nav-item">
                            <a href="{{ route('jury.marks_register') }}"
                                class="nav-link @if (Request::segment(1) == 'jury' && Request::segment(2) == 'marks_register') active @endif">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Fiche de Jury</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ url('jury/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 7)
                    <li class="nav-item">
                        <a href="{{ url('apparitorat/dashboard') }}"
                            class="nav-link @if (Request::segment(1) == 'apparitorat' && Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fa fa-user-shield"></i>
                            <p>Tableau de bord Apparitorat</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('apparitorat/change_password') }}"
                            class="nav-link @if (Request::segment(2) == 'change_password') active @endif">
                            <i class="nav-icon fa-solid fa-id-card-clip"></i>
                            <p>
                                Changer le mot de passe
                            </p>
                        </a>
                    </li>

                @endif




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

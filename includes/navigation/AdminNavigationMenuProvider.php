<?php

    class AdminNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        // $base_url = 'http://localhost/school-system-dcbt/admin';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/admin/';
        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/admin/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/';
        }

        $logout_url = "";
        // $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/admin/", "", $base_url);
            $logout_url = "$new_url/logout.php";
        }else{
            $logout_url = 'http://localhost/school-system-dcbt/logout.php';
        }

        $dashboard_url = $base_url .  "dashboard/index.php";
        $school_year_url = $base_url .  "school_year/index.php";
        $room_url = $base_url .  "room/index.php";
        $department_url = $base_url .  "department/index.php";
        $requirement_url = $base_url .  "requirement/index.php";
        $program_url = $base_url .  "program/shs_index.php";
        $teacher_url = $base_url .  "teacher/index.php";
        $course_url = $base_url .  "course/index.php";
        $grade_module_url = $base_url .  "grade/index.php";
        $class_module_url = $base_url .  "classlist/index.php";
        $user_log_url = $base_url .  "log/index.php";
        $admin_users_url = $base_url .  "admin_users/index.php";

        // SHS Default -> More SHS Students than Tertiary.
        $subject_url = $base_url .  "subject/shs_index.php";
        $section_url = $base_url .  "section/shs_index.php";
        $schedule_url = $base_url .  "schedule/index.php";
        $account_url = $base_url .  "account/index.php";

        // $class = "navigationItem ";
  
        $sideBarNavigationItem = Helper::createNavByIconArc("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
        
        $sideBarNavigationItem .= Helper::createNavByIconArc("School Year", 
            "bi bi-calendar icon ", $school_year_url, Constants::$navigationClass . Helper::GetActiveClass($page, "school_year"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Department", 
            "bi bi-people", $department_url, Constants::$navigationClass . Helper::GetActiveClass($page, "department"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Requirement", 
            "bi bi-file icon", $requirement_url, Constants::$navigationClass . Helper::GetActiveClass($page, "requirement"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Room", 
            "bi bi-house", $room_url, Constants::$navigationClass . Helper::GetActiveClass($page, "room"));


        $sideBarNavigationItem .= Helper::createNavByIconArc("Teacher", 
            "bi bi-person icon ", $teacher_url, Constants::$navigationClass . Helper::GetActiveClass($page, "teacher"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Grades", 
            "bi bi-collection", $grade_module_url, Constants::$navigationClass . Helper::GetActiveClass($page, "grade"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Classlist", 
            "bi bi-collection", $class_module_url, Constants::$navigationClass . Helper::GetActiveClass($page, "classlist"));


        // $sideBarNavigationItem .= Helper::createNavByIconArc("Courses", 
        //     "bi bi-book icon ", $course_url, Constants::$navigationClass . Helper::GetActiveClass($page, "course"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Programs", 
            "bi bi-book icon", $program_url, Constants::$navigationClass . Helper::GetActiveClass($page, "program"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Subject", 
            "bi bi-file icon ", $subject_url, Constants::$navigationClass . Helper::GetActiveClass($page, "subject"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Section", 
            "bi bi-person-plus-fill icon", $section_url, Constants::$navigationClass . Helper::GetActiveClass($page, "section"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Schedule", 
            "bi bi-clock icon", $schedule_url, Constants::$navigationClass . Helper::GetActiveClass($page, "schedule"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("Account", 
            "bi bi-person-circle", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "account"));

        $sideBarNavigationItem .= Helper::createNavByIconArc("User Log", 
            "bi bi-person", $user_log_url, Constants::$navigationClass . Helper::GetActiveClass($page, "log"));

        // $sideBarNavigationItem .= Helper::createNavByIconArc("Admin Users", 
        //     "bi bi-lock", $admin_users_url, Constants::$navigationClass . Helper::GetActiveClass($page, "admin_users"));

        if(User::isAdminLoggedIn()) {
            $sideBarNavigationItem .= Helper::createNavByIconArc("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url, Constants::$navigationClass);
        }

        return "
            <div class='navigationContainer'>
                $sideBarNavigationItem
            </div>
        ";
    }


}

?>
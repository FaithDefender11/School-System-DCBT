<?php

    class AdminNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){


        $base_url = 'http://localhost/school-system-dcbt/admin';

        $dashboard_url = $base_url .  "/dashboard/index.php";
        $school_year_url = $base_url .  "/school_year/index.php";
        $teacher_url = $base_url .  "/teacher/index.php";
        $course_url = $base_url .  "/course/index.php";
        $subject_url = $base_url .  "/subject/index.php";
        $section_url = $base_url .  "/section/index.php";
        $schedule_url = $base_url .  "/schedule/index.php";
        $account_url = $base_url .  "/account/index.php";
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        // $class = "navigationItem ";
  
        $sideBarNavigationItem = Helper::createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
        
        $sideBarNavigationItem .= Helper::createNavByIcon("School Year", 
            "bi bi-calendar icon ", $school_year_url, Constants::$navigationClass . Helper::GetActiveClass($page, "school_year"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Teacher", 
            "bi bi-person icon ", $teacher_url, Constants::$navigationClass . Helper::GetActiveClass($page, "teacher"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Courses", 
            "bi bi-book icon ", $course_url, Constants::$navigationClass . Helper::GetActiveClass($page, "course"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Subject", 
            "bi bi-file icon ", $subject_url, Constants::$navigationClass . Helper::GetActiveClass($page, "subject"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Section", 
            "bi bi-person-plus-fill icon", $section_url, Constants::$navigationClass . Helper::GetActiveClass($page, "section"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Schedule", 
            "bi bi-clock icon", $schedule_url, Constants::$navigationClass . Helper::GetActiveClass($page, "schedule"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Account", 
            "bi bi-person-circle", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "account"));

        if(User::isAdminLoggedIn()) {
            $sideBarNavigationItem .= Helper::createNavByIcon("Log Out", 
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
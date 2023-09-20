<?php

    class StudentElmsNavigationProvider {

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj = null)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create($page){

            $base_url = 'http://localhost/school-system-dcbt/student/';
            $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

            $courses_lms_url = $base_url .  "courses/index.php";
            $dashboard_lms_url = $base_url .  "lms/student_dashboard.php";

            $sideBarNavigationItem = "";

            if(User::IsStudentEnrolledAuthenticatedLMS()) {

                $sideBarNavigationItem .= Helper::createNavByIcon("Courses", 
                    "bi bi-clipboard-data icon",
                    $courses_lms_url,
                    Constants::$navigationClass . Helper::GetActiveClass($page, "courses"));

                $sideBarNavigationItem .= Helper::createNavByIcon("Dashboard", 
                    "bi bi-clipboard-data icon",
                    $dashboard_lms_url,
                    Constants::$navigationClass . Helper::GetActiveClass($page, "lms"));

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
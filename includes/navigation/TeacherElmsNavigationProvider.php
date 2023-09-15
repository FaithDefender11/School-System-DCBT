<?php

    class TeacherElmsNavigationProvider {

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj = null)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create($page){

            $base_url = 'http://localhost/school-system-dcbt/teacher/';
            $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

            $dashboard_lms_url = $base_url .  "dashboard/index.php";
            $classes_lms_url = $base_url .  "class/index.php";

            $sideBarNavigationItem = "";

            if(User::IsTeacherAuthenticated()) {

                $sideBarNavigationItem .= Helper::createNavByIcon("Dashboard", 
                    "bi bi-clipboard-data icon",
                    $dashboard_lms_url,
                    Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));

                // $sideBarNavigationItem .= Helper::createNavByIcon("Classes", 
                //     "bi bi-clipboard-data icon",
                //     $classes_lms_url,
                //     Constants::$navigationClass . Helper::GetActiveClass($page, "class"));

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
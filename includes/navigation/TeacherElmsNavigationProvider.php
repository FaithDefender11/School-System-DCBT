<?php
include_once("../../includes/classes/Teacher.php");
    class TeacherElmsNavigationProvider {

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj = null)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create($page){

            // $base_url = 'http://localhost/school-system-dcbt/teacher/';
            $base_url = "";
            $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

            if ($_SERVER['SERVER_NAME'] === 'localhost') {
                // Running on localhost
                $base_url = 'http://localhost/school-system-dcbt/teacher/';
            } else {
                // Running on web hosting
                // $base_url = 'https://sub.dcbt.online/registrar/';
                $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/teacher/';
            }

            // $logout_url = "http://localhost/school-system-dcbt/logout.php";
            $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

            if ($_SERVER['SERVER_NAME'] !== 'localhost') {
                $new_url = str_replace("/teacher/", "", $base_url);
                $logout_url = "$new_url/lms_logout.php";
            }

            $teacherLoggedInId = isset($_SESSION["teacherLoggedInId"]) 
                ? $_SESSION["teacherLoggedInId"] : "";
            $teacherLoggedInObj = new teacher($this->con, $teacherLoggedInId);
            $teacher_id = $teacherLoggedInObj->GetteacherId();
            $user_lms_url = $base_url . "profile/my_profile.php?id=" . $teacher_id;

            $dashboard_lms_url = $base_url .  "dashboard/index.php";
            $grade_url = $base_url .  "grade/index.php";
            $sideBarNavigationItem = "";

            if(User::IsTeacherAuthenticated()) {

                $sideBarNavigationItem .= Helper::createNavByIcon("Dashboard", 
                    "bi bi-clipboard-data icon",
                    $dashboard_lms_url,
                    Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
 
                $sideBarNavigationItem .= Helper::createNavByIcon("Grade", 
                    "bi bi-book icon",
                    $grade_url,
                    Constants::$navigationClass . Helper::GetActiveClass($page, "grade"));

                $sideBarNavigationItem .= Helper::createNavByIcon("User", 
                    "bi bi-person-circle", 
                    $user_lms_url, 
                    Constants::$navigationClass . Helper::GetActiveClass($page, "user"));

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
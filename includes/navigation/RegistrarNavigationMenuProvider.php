<?php

    class RegistrarNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){


        // $base_url = 'http://localhost/school-system-dcbt/registrar/';
        // $base_url = 'http://' . web_root . '/registrar/';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/registrar/';
        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/registrar/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/registrar/';
        }
        
        $logout_url = "http://localhost/school-system-dcbt/logout.php";

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {
            $new_url = str_replace("/registrar/", "", $base_url);
            $logout_url = "$new_url/logout.php";

        }
        // else{
        //     $logout_url = 'http://localhost/school-system-dcbt/logout.php';
        // }

        $dashboard_url = $base_url .  "dashboard/index.php";
        $admission_url = $base_url .  "admission/evaluation.php";
        $students_url = $base_url .  "student/index.php";
        $section_url = $base_url .  "section/index.php";
        $enrollment_url = $base_url .  "enrollment/index.php";
        $requirement_url = $base_url .  "requirements/index.php";
        $room_url = $base_url .  "room/index.php";
        $waiting_list_url = $base_url .  "waiting_list/index.php";

        // $class = "navigationItem ";
        
        $sideBarNavigationItem = Helper::createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Admission", 
            "bi bi-calendar icon", $admission_url, Constants::$navigationClass . Helper::GetActiveClass($page, "admission"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Students", 
            "bi bi-person icon", $students_url, Constants::$navigationClass . Helper::GetActiveClass($page, "student"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Section", 
            "bi bi-book icon", $section_url, Constants::$navigationClass . Helper::GetActiveClass($page, "section"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Enrollment", 
            "bi bi-clock-history icon", $enrollment_url, Constants::$navigationClass . Helper::GetActiveClass($page, "enrollment"));
        
        $sideBarNavigationItem .= Helper::createNavByIcon("Requirement", 
            "bi bi-file", $requirement_url, Constants::$navigationClass . Helper::GetActiveClass($page, "requirements"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Room", 
            "bi bi-house", $room_url, Constants::$navigationClass . Helper::GetActiveClass($page, "room"));

        // $sideBarNavigationItem .= Helper::createNavByIcon("Waiting List", 
        //     "bi bi-flag", $waiting_list_url, Constants::$navigationClass . Helper::GetActiveClass($page, "waiting_list"));



        if(User::isRegistrarLoggedIn()) {
            // $sideBarNavigationItem .= Helper::createNavItem("Settings", "assets/images/icons/settings.png", "settings.php");
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
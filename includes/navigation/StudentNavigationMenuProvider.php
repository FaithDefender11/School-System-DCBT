<?php

    class StudentNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj = null)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        // $base_url = 'http://localhost/school-system-dcbt/student/';
        // $logout_url = 'http://localhost/school-system-dcbt/logout.php';


        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/student/';
        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/registrar/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
        }
        
        $logout_url = "http://localhost/school-system-dcbt/logout.php";

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/student/", "", $base_url);
            $logout_url = "$new_url/logout.php";
        }


        $ongoing_enrollment_url = $base_url .  "ongoing_enrollment/procedure.php?information=show";
        $registration_enrollment_url = $base_url .  "registration/index.php";
        $requirements_url = $base_url .  "requirements/index.php";
        $pending_enrollment_url = $base_url .  "tentative/process.php";
        $dashboard_url = $base_url .  "dashboard/index.php";
        
        $sideBarNavigationItem = "";

        if(User::IsStudentEnrolledAuthenticated()) {

            $sideBarNavigationItem .= Helper::createNavByIconARC("Registration", 
                "bi bi-clipboard-data icon",
                $registration_enrollment_url,Constants::$navigationClass . Helper::GetActiveClass($page, "registration"));
            
            $sideBarNavigationItem .= Helper::createNavByIconARC("Apply Semester", 
                "bi bi-clipboard-data icon",
                $ongoing_enrollment_url, Constants::$navigationClass . Helper::GetActiveClass($page, "ongoing_enrollment"));
            
            $sideBarNavigationItem .= Helper::createNavByIconARC("Requirements", 
                "bi bi-clipboard-data icon",
                $requirements_url, Constants::$navigationClass . Helper::GetActiveClass($page, "requirements"));


            $sideBarNavigationItem .= Helper::createNavByIconARC("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url, Constants::$navigationClass);
        } 

        if(User::IsStudentPendingAuthenticated()){
            
            $sideBarNavigationItem .= Helper::createNavByIconARC("Registration", 
                "bi bi-clipboard-data icon", $pending_enrollment_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
 
            $sideBarNavigationItem .= Helper::createNavByIconARC("Log Out", 
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
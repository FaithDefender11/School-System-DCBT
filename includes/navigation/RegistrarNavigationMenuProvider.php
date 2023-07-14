<?php

    class RegistrarNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        $base_url = 'http://localhost/school-system-dcbt/registrar/';
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        $dashboard_url = $base_url .  "dashboard/index.php";
        $admission_url = $base_url .  "admission/evaluation.php";
        $students_url = $base_url .  "student/index.php";
        $section_url = $base_url .  "section/index.php";
        $enrollment_url = $base_url .  "enrollment/index.php";

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
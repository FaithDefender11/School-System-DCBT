<?php

    class StudentNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj = null)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        $base_url = 'http://localhost/school-system-dcbt/student/';
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        $dashboard_url = $base_url .  "dashboard/index.php";

        // $class = "navigationItem ";
        
        $sideBarNavigationItem = "";

        if(User::IsStudentEnrolledAuthenticated()) {

            $sideBarNavigationItem .= Helper::createNavByIcon("Dashboard", 
            "   bi bi-clipboard-data icon", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
            
            $sideBarNavigationItem .= Helper::createNavByIcon("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url, Constants::$navigationClass);
        }

        if(User::IsStudentPendingAuthenticated()){
            
            $sideBarNavigationItem .= Helper::createNavByIcon("Registration", 
                "bi bi-clipboard-data icon", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
 
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
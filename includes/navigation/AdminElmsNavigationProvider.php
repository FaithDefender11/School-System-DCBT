<?php

    class AdminElmsNavigationProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        // $base_url = 'http://localhost/school-system-dcbt/admin_lms';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/admin_lms';

        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/registrar/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/admin_lms/';
        }

        $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';
        
        if ($_SERVER['SERVER_NAME'] !== 'localhost') {
            $new_url = str_replace("/admin_lms/", "", $base_url);
            $logout_url = "$new_url/logout.php";
        }

        $dashboard_url = $base_url .  "/dashboard/index.php";
        $subject_url = $base_url .  "/subject/shs_index.php";
        $account_url = $base_url .  "/account/activated.php";
        
 
        // $class = "navigationItem ";
  
        $sideBarNavigationItem = Helper::createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
        
        $sideBarNavigationItem .= Helper::createNavByIcon("Subject", 
            "bi bi-calendar icon ", $subject_url, Constants::$navigationClass . Helper::GetActiveClass($page, "subject"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Account", 
            "bi bi-person ", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "account"));

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
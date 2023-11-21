<?php

    class SuperAdminNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        // $base_url = 'http://localhost/school-system-dcbt/super_admin';
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/super_admin/';
       

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/super_admin/';
        }

        $logout_url = "http://localhost/school-system-dcbt/logout.php";
        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/super_admin/", "", $base_url);
            $logout_url = "$new_url/logout.php";
        }

        // $class = "navigationItem ";

        $dashboard_url = $base_url .  "dashboard/index.php";
        $account_url = $base_url .  "users/index.php";
        $account_url = $base_url .  "form/index.php";

        $sideBarNavigationItem = Helper::createNavByIconARC("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
  
        $sideBarNavigationItem .= Helper::createNavByIconARC("Users", 
            "bi bi-person-circle", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "users"));


        $sideBarNavigationItem .= Helper::createNavByIconARC("Form", 
            "bi bi-file", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "form"));

        if(User::isSuperAdminLoggedIn()) {
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
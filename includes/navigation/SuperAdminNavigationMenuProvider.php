<?php

    class SuperAdminNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        $base_url = 'http://localhost/school-system-dcbt/super_admin';

        $dashboard_url = $base_url .  "/dashboard/index.php";
        $account_url = $base_url .  "/users/index.php";
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        // $class = "navigationItem ";
  
        $sideBarNavigationItem = Helper::createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon ", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));
  
        $sideBarNavigationItem .= Helper::createNavByIcon("Users", 
            "bi bi-person-circle", $account_url, Constants::$navigationClass . Helper::GetActiveClass($page, "users"));

        if(User::isSuperAdminLoggedIn()) {
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
<?php

    class CashierNavigationMenuProvider {

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($page){

        // $base_url = 'http://localhost/school-system-dcbt';
        $base_url = 'http://localhost/school-system-dcbt/cashier';
        $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        $dashboard_url = $base_url .  "/dashboard/index.php";
        $payments_url = $base_url .  "/payment/index.php";
        $payment_history_url = $base_url .  "/payment_history/index.php";

        $sideBarNavigationItem = Helper::createNavByIcon("Dashboard", 
            "bi bi-clipboard-data icon", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));


        $sideBarNavigationItem .= Helper::createNavByIcon("Payment", 
                "bi bi-credit-card", $payments_url, Constants::$navigationClass . Helper::GetActiveClass($page, "payment"));

        $sideBarNavigationItem .= Helper::createNavByIcon("Payment History", 
            "bi bi-clock-history icon", $payment_history_url, Constants::$navigationClass . Helper::GetActiveClass($page, "payment_history"));

     

        // $sideBarNavigationItem = $this->createNavByIcon("Dashboard", 
        //     "bi bi-clipboard-data icon", $dashboard_url);

        // $sideBarNavigationItem .= $this->createNavByIcon("Payment", 
        //     "bi bi-credit-card", $payments_url);

        // $sideBarNavigationItem .= $this->createNavByIcon("History", 
        //     "bi bi-clock-history icon", $payment_history_url);

        if(User::isCashierLoggedIn()) {
            $sideBarNavigationItem .= Helper::createNavByIcon("Log Out", 
                "bi bi-box-arrow-right icon", $logout_url, Constants::$navigationClass);
        }

        return "
            <div class='navigationContainer'>
                $sideBarNavigationItem
            </div>
        ";

    }

    public function createNavItem($text, $icon, $link){
        return "
            <div class='navigationItem'>
                <a href='$link'>
                    <img src='$icon' />
                    <span>$text</span>
                </a>
            </div>
        ";
    }

    public function createNavByIcon($text, $icon, $link){
        return "
            <div class='navigationItem'>
                <a href='$link'>
                    <i style='color: white;' class='$icon'></i>
                    <span>$text</span>
                </a>
            </div>
        ";
    }

}

?>
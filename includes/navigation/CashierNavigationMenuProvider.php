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
        // $base_url = 'http://localhost/school-system-dcbt/cashier';
        // $logout_url = 'http://localhost/school-system-dcbt/logout.php';

        $base_url = "";
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Running on localhost
            $base_url = 'http://localhost/school-system-dcbt/cashier/';
        } else {
            // Running on web hosting
            // $base_url = 'https://sub.dcbt.online/cashier/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/cashier/';
        }


        $logout_url = "http://localhost/school-system-dcbt/logout.php";
        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/cashier/", "", $base_url);
            $logout_url = "$new_url/logout.php";
        }
        // else{
        //     $logout_url = 'http://localhost/school-system-dcbt/logout.php';
        // }

        $dashboard_url = $base_url .  "dashboard/index.php";
        $payments_url = $base_url .  "payment/index.php";
        $installment_url = $base_url .  "installment/index.php";
        $payment_history_url = $base_url .  "payment_history/index.php";

        $sideBarNavigationItem = Helper::createNavByIconARC("Dashboard", 
            "bi bi-clipboard-data icon", $dashboard_url, Constants::$navigationClass . Helper::GetActiveClass($page, "dashboard"));

        

        $sideBarNavigationItem .= Helper::createNavByIconARC("Payment", 
                "bi bi-credit-card", $payments_url, Constants::$navigationClass . Helper::GetActiveClass($page, "payment"));

        $sideBarNavigationItem .= Helper::createNavByIconARC("Payment History", 
            "bi bi-clock-history icon", $payment_history_url, Constants::$navigationClass . Helper::GetActiveClass($page, "payment_history"));

        $sideBarNavigationItem .= Helper::createNavByIconARC("Installment", 
            "bi bi-cash-stack", $installment_url, Constants::$navigationClass . Helper::GetActiveClass($page, "installment"));

        // $sideBarNavigationItem = $this->createNavByIconARC("Dashboard", 
        //     "bi bi-clipboard-data icon", $dashboard_url);

        // $sideBarNavigationItem .= $this->createNavByIconARC("Payment", 
        //     "bi bi-credit-card", $payments_url);

        // $sideBarNavigationItem .= $this->createNavByIconARC("History", 
        //     "bi bi-clock-history icon", $payment_history_url);

        if(User::isCashierLoggedIn()) {
            $sideBarNavigationItem .= Helper::createNavByIconARC("Log Out", 
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

    public function createNavByIconARC($text, $icon, $link){
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
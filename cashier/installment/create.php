
<?php 

    include_once('../../includes/cashier_header.php');



    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['installment_btn'])
        && isset($_POST['default_payment_due'])
        && isset($_POST['option'])
        && isset($_POST['enable'])
        ){


        $default_payment_due = $_POST['default_payment_due'];
        $option = $_POST['option'];
        $enable = $_POST['enable'];

        $option = strtolower(str_replace(' ', '_', trim(strtolower($option))));

        $sql = $con->prepare("INSERT INTO installment
            (default_payment_due, option, enable)
            VALUES(:default_payment_due, :option, :enable)
        ");
                    

        $sql->bindValue(":default_payment_due", $default_payment_due);
        $sql->bindValue(":option", $option);
        $sql->bindValue(":enable", $enable);
        $sql->execute();

        if($sql->rowCount() > 0){
            Alert::success("Installment has been created.", "index.php");
            exit();
        }
    }


?>

<div class="content">
    <nav>
        <a href="index.php">
            <i class="bi bi-arrow-return-left fa-1x"></i>
            <h3>Back</h3>
        </a>
    </nav>
    
    <main>
        <div class="floating">

            <header>
                <div class="title">
                    <h4 class="text-primary text-center">Installment Maintenance</h4>
                </div>
            </header>
         
            <form method="POST">

                <main>
                  
                    <div class='form-group mb-2'>
                        <label for='option'>* Installment Name</label>
                        <input class='form-control' id="option" required type='text' placeholder='' name='option'>
                    </div>
                  
                    <div class='form-group mb-2'>
                        <label for='default_payment_due'>* To Pay Date</label>
                        <input class='form-control' id="default_payment_due" required type='date' placeholder='' name='default_payment_due'>
                    </div>

                    <div class="mt-1 form-group mb-2">
                        <span>
                            <label>* Enabled:</label>
                            <input type="radio" id="enabled-yes" name="enable" value="1">
                            <label for="enabled-yes">Yes</label>
                            <input type="radio" checked id="enabled-no" name="enable" value="0">
                            <label for="enabled-no">No</label>
                        </span>
                    </div>

                </main>

                <div style="margin-bottom: -20px; margin-top: 20px;" class="action modal-footer">
                    <button name="installment_btn"
                        type="submit" class="default large clean">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
    




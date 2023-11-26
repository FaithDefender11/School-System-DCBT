<?php 

    include_once('../../includes/cashier_header.php');



?>

 
<div class="content">

    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Installment</h3>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="default large">+ Add new</button>
                    </a>
                </div>

            </header>
            
            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Installment Name</th>
                            <th>To Pay Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $SHS = "Senior High School";
                            $Tertiary = "Tertiary";

                            $query = $con->prepare("SELECT * FROM installment
                           

                            ");

                         
                            $query->execute();

                            if($query->rowCount() > 0){

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $installment_id = $row['installment_id'];
                                        $option = $row['option'];

                                        $default_payment_due_db = $row['default_payment_due'];
                                        
                                        $default_payment_due = date("M d, Y", strtotime($default_payment_due_db));

                                        $enableButton = "";

                                        // if()

                                        $removeDepartmentBtnC = "removeDepartmentBtn($installment_id)";
                                       
                                        $removeDepartmentBtn = "
                                            <button onclick='$removeDepartmentBtnC' class='danger'>
                                                <i class='fas fa-ban'></i>
                                            </button>
                                        ";
                                        $removeDepartmentBtn = "";

                                        $option = ucfirst($option);

                                                    $transformedString = ucwords(str_replace('_', ' ', ucfirst($option)));


                                    echo "
                                        <tr>
                                            <td>$transformedString</td>
                                            <td>$default_payment_due</td>
                                            
                                            <td>
                                                <a href='edit.php?id=$installment_id'>
                                                    <button class='information'>
                                                        <i class='fas fa-pen'></i>
                                                    </button>
                                                </a>
                                                $removeDepartmentBtn
                                            </td>
                                        </tr>
                                    ";
                                    
                                }
                            }

                        ?>
                    </tbody>
                </table>

            </main>
            
        </div>
    </main>
</div>





<?php include_once('../../includes/footer.php') ?>

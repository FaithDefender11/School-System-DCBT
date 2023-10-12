<?php 

    include_once('../../includes/admin_elms_header.php');


    // echo $_SESSION['role'];
    
    

?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Department</h3>
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
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $SHS = "Senior High School";
                            $Tertiary = "Tertiary";

                            $query = $con->prepare("SELECT * FROM department
                                WHERE department_name =:condition1
                                OR department_name =:condition2
                            ");

                            $query->bindParam(":condition1", $SHS);
                            $query->bindParam(":condition2", $Tertiary);
                            $query->execute();

                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $department_id = $row['department_id'];
                                    $department_name = $row['department_name'];

                                $removeDepartmentBtn = "removeDepartmentBtn($department_id)";
                                echo "
                                <tr>
                                    <td>$department_id</td>
                                    <td>$department_name</td>
                                    <td>
                                        <a href='edit.php?id=$department_id'>
                                            <button class='information'>
                                                <i class='fas fa-pen'></i>
                                            </button>
                                        </a>
                                        <button onclick='$removeDepartmentBtn' class='danger'>
                                                <i class='fas fa-trash'></i>
                                        </button>
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
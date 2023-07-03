<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Department.php');
    // include_once('../../assets/images/');

    if(isset($_GET['id'])){

        $department_id = $_GET['id'];

        $department = new Department($con, $department_id);

        $department_name = $department->GetDepartmentName();

        if(isset($_POST['edit_department_btn_' . $department_id])){

            $department_name = $_POST['department_name'];

            $statement = $con->prepare("UPDATE department 
                SET department_name = :new_department_name 
                WHERE department_id = :department_id");

            // Assuming you have an 'id' column in the 'department' table to uniquely identify the department
            $statement->bindParam(":new_department_name", $department_name);
            $statement->bindParam(":department_id", $department_id);

            if ($statement->execute()) {
                Alert::success("Department Successfully Updated", "index.php");
                exit();
            } else {
                Alert::error("Error Occured", "index.php");
                exit();
            }
        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
                
                <div class='card'>
                    <hr>
                    <a href="index.php">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Edit  Department</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for=''>Department Name</label>
                                <input class='form-control' type='text' placeholder='' value="<?php echo $department_name;?>" name='department_name'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='edit_department_btn_<?php echo $department_id;?>'>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php

    }

?>


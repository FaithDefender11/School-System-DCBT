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
        <body>
            <div class="content">
                <nav>
                    <a href="index.php">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <main>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <span>
                                <label for="dept_name">Department Name</label>
                                <div>
                                    <input type="text" name="department_name" placeholder="" value="<?php echo $department_name;?>">
                                </div>
                            </span>
                        </div>
                        <div class="action">
                            <button type="submit" class="clean large" name="edit_department_btn_<?php echo $department_id;?>">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </body>
        <?php

    }

?>


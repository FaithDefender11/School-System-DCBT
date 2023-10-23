

<?php 

    include_once('../../includes/admin_header.php');
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

                <?php 
                
                    $twoCount = [1,2];

                    for ($i=0; $i < 2; $i++) { 
                        # code...
                        ?>

                        <br>
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

                        <?php
                    }
                ?>

                

            </main>
            
        </div>
    </main>
</div>


<script>
    
    function removeDepartmentBtn(department_id){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed Department ID: ${department_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/department/remove_department.php",
                        type: 'POST',
                        data: {
                            department_id
                        },
                        success: function(response) {
                            response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#department_table').load(
                                    location.href + ' #department_table'
                                );
                            });
                            }

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

</script>
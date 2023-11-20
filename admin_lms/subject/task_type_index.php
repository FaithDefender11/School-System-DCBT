

<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SchoolYear.php');

?>


<div class="content">
    <main>
        <div class="floating">
            <header>
                
                <div class="title">
                    <h5>Task Categories</h5>
                </div>

                <div class="action">
                    <a href="task_type_create.php">
                        <button type="button" class="clean large success">+ Add new</button>
                    </a>
                </div>
                
            </header>

            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Enabled</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        
                            $query = $con->prepare("SELECT * FROM task_type
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $task_type_id = $row['task_type_id'];
                                        $task_name = $row['task_name'];
                                        $enabled = $row['enabled'];

                                        $status = "";

                                        if($enabled == 1){
                                            $status = "
                                                <i class='fas fa-check' style='color: green'></i>
                                            ";
                                        }else{
                                            $status = "
                                                <i class='fas fa-times' style='color: orangered'></i>
                                            ";
                                        }

                                        $removeTaskType = "removeTaskType($task_type_id)";
                                        
                                        // <button onclick='$removeTaskType' class='danger'>
                                        //                     <i class='fas fa-trash'></i>
                                        //             </button>
                                        echo "
                                            <tr>
                                                <td>$task_name</td>
                                                <td>$status</td>
                                                <td>
                                                    <a href='task_type_edit.php?id=$task_type_id'>
                                                        <button class='information'>
                                                            <i class='fas fa-pen'></i>
                                                        </button>
                                                    </a>
                                                    
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

<script>
    
    function removeTaskType(task_type_id){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed selected Task`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/department/remove_task.php",
                        type: 'POST',
                        data: {
                            task_type_id
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
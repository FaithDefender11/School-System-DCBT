

<?php 

    include_once('../../includes/admin_header.php');

?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Requirement</h3>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="default large">+ Add new</button>
                    </a>
                </div>

            </header>
            
            <main>

                <table id="requirement_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                         

                            $query = $con->prepare("SELECT * 

                                FROM requirement
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){

                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                $requirement_id = $row['requirement_id'];
                                $requirement_name = $row['requirement_name'];
                                $status = $row['status'];
                                $is_enabled = $row['is_enabled'];

                                $removeSelectedRequirement = "removeSelectedRequirement($requirement_id, \"$requirement_name\")";
                                
                                $is_enabled_output = "";
                                if($is_enabled === 1){
                                    $is_enabled_output = "
                                        <i style='color: green;' class='fas fa-check'></i>
                                    ";
                                }else{
                                    $is_enabled_output = "
                                        <i style='color: orange;' class='fas fa-times'></i>
                                    ";
                                }

                                $remove_btn = "";

                                if($is_enabled === 0){

                                    $remove_btn = "
                                            <button  onclick='$removeSelectedRequirement' class='btn btn-danger btn-sm'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                    ";
                                }


                                $url = "edit.php?id=";

                                echo "
                                <tr>
                                    <td>$requirement_name</td>
                                    <td>$status</td>
                                    <td>$is_enabled_output</td>
                                    <td>

                                        <a href='#'>
                                            <button class='btn btn-sm btn-primary'>
                                                <i class='fas fa-pen'></i>
                                            </button>
                                        </a>
                                        
                                        $remove_btn
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
    
    function removeSelectedRequirement(requirement_id, requirement_name){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed ${requirement_name}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/requirements/removeSelectedRequirement.php",
                        type: 'POST',
                        data: {
                            requirement_id, requirement_name
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

                                $('#requirement_table').load(
                                    location.href + ' #requirement_table'
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
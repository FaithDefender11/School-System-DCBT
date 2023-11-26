

<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/StudentRequirement.php');


    $studentRequirement = new StudentRequirement($con);

    $universalRequirements = $studentRequirement->GetRequirements("Universal", "Universal");

    $standardRequirementsSHS = $studentRequirement->GetStandardRequirementsForEducationType("Standard", "Tertiary");
    $standardRequirementsUniversal = $studentRequirement->GetRequirementsForStandardUniversal();

    // var_dump($standardRequirementsSHS);

    $tertiarySHSRequirements = array_merge($universalRequirements, $standardRequirementsUniversal, $standardRequirementsSHS);


?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Requirements for &nbsp; <span style="font-size: 16px;" class="text-primary">SHS <a style="color: inherit;" href='shs_standard.php'>Standard</a> , <a style="color: inherit;" href="shs_transferee.php">Transferee</a> | Tertiary <a style="text-decoration: underline; color: inherit;" href="tertiary_standard.php">Standard</a>, <a style="color: inherit;" href="tertiary_transferee.php">Transferee</a></span></h3>

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
                            <th>#</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            if(count($tertiarySHSRequirements) > 0){

                                $i = 0;
                                foreach ($tertiarySHSRequirements as $key => $row) {

                                    $i++;
                                    $requirement_id = $row['requirement_id'];
                                    $requirement_name = $row['requirement_name'];
                                    $status = $row['status'];
                                    $education_type = $row['education_type'];
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

                                        // <td>$is_enabled_output</td>

                                    echo "
                                    <tr>
                                        <td>$i</td>
                                        <td>$requirement_name</td>
                                        
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
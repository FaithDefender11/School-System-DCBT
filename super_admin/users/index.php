 
<?php 

    include_once('../../includes/super_admin_header.php');
    include_once('../../includes/classes/User.php');


    $user = new User($con);


    $check = $user->GenerateUniqueUsersId();

    // var_dump($check);

    // echo $_SESSION['adminUserId'];
?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Admin Users</h3>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="clean large success">+ Add new</button>
                    </a>
                </div>
            </header>
            <main>
                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $query = $con->prepare("SELECT * FROM users
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){

                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                $fullname = $row['firstName'] . " " .  $row['lastName'];
                                $username = $row['username'];
                                $role = $row['role'];
                                $user_id = $row['user_id'];

                                $removeUser = "removeUser($user_id)";

                                $resetPasswordBtn = "resetPasswordBtn($user_id)";
                                
                                echo "
                                <tr>
                                    <td>$user_id</td>
                                    <td>$fullname</td>
                                    <td>$username</td>
                                    <td>$role</td>
                                    <td>
                                        <a href='edit.php?id=$user_id'>
                                            <button class='btn btn-primary'>
                                                <i class='fas fa-pen'></i>
                                            </button>
                                        </a>
                                        <button onclick='$removeUser' class='btn btn-danger'>
                                                <i class='fas fa-trash'></i>
                                        </button>
                                        <button onclick='$resetPasswordBtn' class='btn btn-info'>
                                                <i class='fas fa-undo'></i>
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


<script>

    function removeUser(user_id){

        var user_id = parseInt(user_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove user?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/super_admin/removeUser.php",
                        type: 'POST',
                        data: {
                            user_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}

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
    
    function resetPasswordBtn(user_id){

        var user_id = parseInt(user_id);

            Swal.fire({
                icon: 'question',
                title: `This will reset user password?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/super_admin/reset_user_password.php",
                        type: 'POST',
                        data: {
                            user_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            // if(response == "success_update"){
                            //     Swal.fire({
                            //     icon: 'success',
                            //     title: `Successfully Updated`,
                            //     showConfirmButton: false,
                            //     timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                            //     toast: true,
                            //     position: 'top-end',
                            //     showClass: {
                            //     popup: 'swal2-noanimation',
                            //     backdrop: 'swal2-noanimation'
                            //     },
                            //     hideClass: {
                            //     popup: '',
                            //     backdrop: ''
                            //     }
                            // }).then((result) => {

                            //     // $('#shs_program_table').load(
                            //     //     location.href + ' #shs_program_table'
                            //     // );

                            //     location.reload();
                            // });}
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
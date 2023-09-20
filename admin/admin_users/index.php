<?php 

    include_once('../../includes/admin_header.php');
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
                            <th>ID</th>
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

                                $removeDepartmentBtn = "removeAdminBtn($user_id)";
                                
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
                                        <button onclick='$removeDepartmentBtn' class='btn btn-danger'>
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